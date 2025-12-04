<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="admin_categories")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/categories/new", name="admin_categories_new")
     */
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/categories/{id}/edit", name="admin_categories_edit")
     */
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/admin/categories/{id}/delete", name="admin_categories_delete", methods={"POST"})
     */
    public function delete(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_categories');
    }
}
