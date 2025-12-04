<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\OrderRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/review")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/product/{productId}/new", name="review_new", methods={"GET", "POST"})
     */
    public function new(
        int $productId,
        Request $request,
        EntityManagerInterface $entityManager,
        OrderRepository $orderRepository,
        ReviewRepository $reviewRepository
    ): Response {
        $user = $this->getUser();
        $product = $entityManager->getRepository(\App\Entity\Product::class)->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Check if user has purchased this product
        $hasPurchased = $this->hasUserPurchasedProduct($user, $product, $orderRepository);
        if (!$hasPurchased) {
            $this->addFlash('error', 'You can only review products you have purchased.');
            return $this->redirectToRoute('product_detail', ['id' => $productId]);
        }

        // Check if user already reviewed this product
        $existingReview = $reviewRepository->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if ($existingReview) {
            $this->addFlash('info', 'You have already reviewed this product.');
            return $this->redirectToRoute('product_detail', ['id' => $productId]);
        }

        $review = new Review();
        $review->setUser($user);
        $review->setProduct($product);

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // For now, auto-approve reviews. In production, you might want admin approval
            $review->setApproved(true);

            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Your review has been submitted successfully!');
            return $this->redirectToRoute('product_detail', ['id' => $productId]);
        }

        return $this->render('review/form.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="review_edit", methods={"GET", "POST"})
     */
    public function edit(
        Review $review,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Ensure user owns this review
        if ($review->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only edit your own reviews.');
        }

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Your review has been updated successfully!');
            return $this->redirectToRoute('profile_reviews');
        }

        return $this->render('review/form.html.twig', [
            'form' => $form->createView(),
            'product' => $review->getProduct(),
            'review' => $review,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="review_delete", methods={"POST"})
     */
    public function delete(
        Review $review,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Ensure user owns this review
        if ($review->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own reviews.');
        }

        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();

            $this->addFlash('success', 'Your review has been deleted successfully!');
        }

        return $this->redirectToRoute('profile_reviews');
    }

    /**
     * Check if user has purchased the product
     */
    private function hasUserPurchasedProduct($user, $product, OrderRepository $orderRepository): bool
    {
        // Find all completed orders for this user
        $orders = $orderRepository->findBy([
            'user' => $user,
            'status' => 'Completed', // Assuming 'Completed' is the status for finished orders
        ]);

        foreach ($orders as $order) {
            foreach ($order->getOrderItems() as $orderItem) {
                if ($orderItem->getProduct() === $product) {
                    return true;
                }
            }
        }

        return false;
    }
}
