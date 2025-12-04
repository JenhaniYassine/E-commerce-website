<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\PaymentMethod;
use App\Entity\Wishlist;
use App\Form\AddressType;
use App\Form\ChangePasswordType;
use App\Form\PaymentMethodType;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="profile_index")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/orders", name="profile_orders")
     */
    public function orders(): Response
    {
        $user = $this->getUser();
        $orders = $user->getOrders();

        return $this->render('profile/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/addresses", name="profile_addresses")
     */
    public function addresses(): Response
    {
        $user = $this->getUser();
        $addresses = $user->getAddresses();

        return $this->render('profile/addresses.html.twig', [
            'addresses' => $addresses,
        ]);
    }

    /**
     * @Route("/addresses/new", name="profile_addresses_new")
     */
    public function newAddress(Request $request, EntityManagerInterface $entityManager): Response
    {
        $address = new Address();
        $address->setUser($this->getUser());

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('success', 'Address added successfully!');
            return $this->redirectToRoute('profile_addresses');
        }

        return $this->render('profile/address_form.html.twig', [
            'form' => $form->createView(),
            'address' => $address,
        ]);
    }

    /**
     * @Route("/addresses/{id}/edit", name="profile_addresses_edit")
     */
    public function editAddress(Request $request, Address $address, EntityManagerInterface $entityManager): Response
    {
        // Ensure user owns this address
        if ($address->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Address updated successfully!');
            return $this->redirectToRoute('profile_addresses');
        }

        return $this->render('profile/address_form.html.twig', [
            'form' => $form->createView(),
            'address' => $address,
        ]);
    }

    /**
     * @Route("/addresses/{id}/delete", name="profile_addresses_delete", methods={"POST"})
     */
    public function deleteAddress(Request $request, Address $address, EntityManagerInterface $entityManager): Response
    {
        // Ensure user owns this address
        if ($address->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $entityManager->remove($address);
            $entityManager->flush();

            $this->addFlash('success', 'Address deleted successfully!');
        }

        return $this->redirectToRoute('profile_addresses');
    }

    /**
     * @Route("/payment-methods", name="profile_payment_methods")
     */
    public function paymentMethods(): Response
    {
        $user = $this->getUser();
        $paymentMethods = $user->getPaymentMethods();

        return $this->render('profile/payment_methods.html.twig', [
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * @Route("/payment-methods/new", name="profile_payment_methods_new")
     */
    public function newPaymentMethod(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paymentMethod = new PaymentMethod();
        $paymentMethod->setUser($this->getUser());

        $form = $this->createForm(PaymentMethodType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paymentMethod);
            $entityManager->flush();

            $this->addFlash('success', 'Payment method added successfully!');
            return $this->redirectToRoute('profile_payment_methods');
        }

        return $this->render('profile/payment_method_form.html.twig', [
            'form' => $form->createView(),
            'paymentMethod' => $paymentMethod,
        ]);
    }

    /**
     * @Route("/payment-methods/{id}/edit", name="profile_payment_methods_edit")
     */
    public function editPaymentMethod(Request $request, PaymentMethod $paymentMethod, EntityManagerInterface $entityManager): Response
    {
        // Ensure user owns this payment method
        if ($paymentMethod->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(PaymentMethodType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Payment method updated successfully!');
            return $this->redirectToRoute('profile_payment_methods');
        }

        return $this->render('profile/payment_method_form.html.twig', [
            'form' => $form->createView(),
            'paymentMethod' => $paymentMethod,
        ]);
    }

    /**
     * @Route("/payment-methods/{id}/delete", name="profile_payment_methods_delete", methods={"POST"})
     */
    public function deletePaymentMethod(Request $request, PaymentMethod $paymentMethod, EntityManagerInterface $entityManager): Response
    {
        // Ensure user owns this payment method
        if ($paymentMethod->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$paymentMethod->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paymentMethod);
            $entityManager->flush();

            $this->addFlash('success', 'Payment method deleted successfully!');
        }

        return $this->redirectToRoute('profile_payment_methods');
    }

    /**
     * @Route("/wishlist", name="profile_wishlist")
     */
    public function wishlist(): Response
    {
        $user = $this->getUser();
        $wishlists = $user->getWishlists();

        return $this->render('profile/wishlist.html.twig', [
            'wishlists' => $wishlists,
        ]);
    }

    /**
     * @Route("/wishlist/add/{productId}", name="profile_wishlist_add")
     */
    public function addToWishlist(int $productId, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $product = $entityManager->getRepository(\App\Entity\Product::class)->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Check if already in wishlist
        $existingWishlist = $entityManager->getRepository(Wishlist::class)->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if (!$existingWishlist) {
            $wishlist = new Wishlist();
            $wishlist->setUser($user);
            $wishlist->setProduct($product);

            $entityManager->persist($wishlist);
            $entityManager->flush();

            $this->addFlash('success', 'Product added to wishlist!');
        } else {
            $this->addFlash('info', 'Product is already in your wishlist.');
        }

        return $this->redirectToRoute('product_detail', ['id' => $productId]);
    }

    /**
     * @Route("/wishlist/remove/{id}", name="profile_wishlist_remove")
     */
    public function removeFromWishlist(Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
        // Ensure user owns this wishlist item
        if ($wishlist->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($wishlist);
        $entityManager->flush();

        $this->addFlash('success', 'Product removed from wishlist!');
        return $this->redirectToRoute('profile_wishlist');
    }

    /**
     * @Route("/reviews", name="profile_reviews")
     */
    public function reviews(): Response
    {
        $user = $this->getUser();
        $reviews = $user->getReviews();

        return $this->render('profile/reviews.html.twig', [
            'user' => $user,
            'reviews' => $reviews,
        ]);
    }

    /**
     * @Route("/settings", name="profile_settings")
     */
    public function settings(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $profileForm = $this->createForm(ProfileType::class, $user);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('profile_settings');
        }

        $passwordForm = $this->createForm(ChangePasswordType::class);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $data = $passwordForm->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $data['newPassword']));

            $entityManager->flush();

            $this->addFlash('success', 'Password changed successfully!');
            return $this->redirectToRoute('profile_settings');
        }

        return $this->render('profile/settings.html.twig', [
            'profileForm' => $profileForm->createView(),
            'passwordForm' => $passwordForm->createView(),
        ]);
    }
}
