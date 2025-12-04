<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/products')]
#[IsGranted('ROLE_ADMIN')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository, PaginatorInterface $paginator): Response
    {
        $query = $productRepository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('product/index.html.twig', [
            'products' => $pagination,
        ]);
    }

    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $fileMimeType = $imageFile->getMimeType();
                
                if (!in_array($fileMimeType, $allowedMimeTypes)) {
                    $this->addFlash('error', 'Invalid image file type. Only JPEG, PNG, GIF, and WebP are allowed.');
                    return $this->redirectToRoute('product_new');
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Could not upload image file.');
                    return $this->redirectToRoute('product_new');
                }

                $product->setImage($newFilename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product created successfully!');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $fileMimeType = $imageFile->getMimeType();
                
                if (!in_array($fileMimeType, $allowedMimeTypes)) {
                    $this->addFlash('error', 'Invalid image file type. Only JPEG, PNG, GIF, and WebP are allowed.');
                    return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Could not upload image file.');
                    return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
                }

                $product->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Product updated successfully!');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product deleted successfully!');
        }

        return $this->redirectToRoute('product_index');
    }

    #[Route('/{id}/toggle', name: 'product_toggle', methods: ['POST'])]
    public function toggle(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('toggle'.$product->getId(), $request->request->get('_token'))) {
            $product->setStatus(!$product->isStatus());
            $entityManager->flush();

            $status = $product->isStatus() ? 'activated' : 'hidden';
            $this->addFlash('success', 'Product ' . $status . ' successfully!');
        }

        return $this->redirectToRoute('product_index');
    }
}
