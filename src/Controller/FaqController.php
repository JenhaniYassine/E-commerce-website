<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    /**
     * @Route("/faq", name="faq")
     */
    public function index(FaqRepository $faqRepository): Response
    {
        $faqs = $faqRepository->findBy(['isActive' => true], ['category' => 'ASC', 'createdAt' => 'ASC']);

        // Group FAQs by category
        $groupedFaqs = [];
        foreach ($faqs as $faq) {
            $groupedFaqs[$faq->getCategory()][] = $faq;
        }

        return $this->render('faq/index.html.twig', [
            'groupedFaqs' => $groupedFaqs,
        ]);
    }
}
