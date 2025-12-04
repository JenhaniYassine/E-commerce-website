<?php

namespace App\Controller;

use App\Entity\OrderIssue;
use App\Form\OrderIssueType;
use App\Repository\OrderIssueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class OrderIssueController extends AbstractController
{
    /**
     * @Route("/order-issue", name="order_issue")
     */
    public function report(Request $request, EntityManagerInterface $entityManager): Response
    {
        $orderIssue = new OrderIssue();
        $form = $this->createForm(OrderIssueType::class, $orderIssue, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderIssue->setUser($this->getUser());
            $orderIssue->setStatus('open');

            $entityManager->persist($orderIssue);
            $entityManager->flush();

            $this->addFlash('success', 'Your order issue has been reported successfully. We will review it and get back to you soon.');

            return $this->redirectToRoute('profile_orders');
        }

        return $this->render('order_issue/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
