<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(\App\Repository\ProductRepository $productRepository, \App\Repository\CategoryRepository $categoryRepository): Response
    {
        // If user is already logged in, redirect to appropriate dashboard
        if ($this->getUser()) {
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('admin_dashboard');
            }
            return $this->redirectToRoute('dashboard');
        }

        // For non-logged-in users, show the home page with featured products
        $products = $productRepository->findBy(['status' => true], ['createdAt' => 'DESC'], 8);
        $categories = $categoryRepository->findAll();
        $newArrivals = $productRepository->findNewArrivals(4);
        $bestSellers = $productRepository->findBestSellers(4);

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'newArrivals' => $newArrivals,
            'bestSellers' => $bestSellers,
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(\App\Repository\ProductRepository $productRepository, \App\Repository\CategoryRepository $categoryRepository, \App\Repository\OrderRepository $orderRepository, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        $user = $this->getUser();

        // Get personalized recommendations
        $recommendations = [];
        if ($user) {
            $recommendations = $productRepository->findRecommendations($user->getId(), 8);
        }

        // Fallback to new arrivals if no recommendations
        if (empty($recommendations)) {
            $recommendations = $productRepository->findNewArrivals(8);
        }

        // Get analytics data
        $analytics = [];
        if ($user) {
            $analytics = [
                'orderStats' => $orderRepository->getUserOrderStats($user->getId()),
                'monthlySpending' => $orderRepository->getUserMonthlySpending($user->getId()),
                'orderFrequency' => $orderRepository->getUserOrderFrequency($user->getId()),
                'categoryPreferences' => $productRepository->getUserCategoryPreferences($user->getId()),
                'topProducts' => $productRepository->getUserTopProducts($user->getId()),
            ];
        }

        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'recommendations' => $recommendations,
            'analytics' => $analytics,
        ]);
    }

    /**
     * @Route("/shop", name="shop")
     */
    public function shop(\App\Repository\ProductRepository $productRepository, \App\Repository\CategoryRepository $categoryRepository, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        // Get query parameters
        $categoryId = $request->query->get('category');
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'ASC');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 12; // Products per page

        // Build filters array
        $filters = [];
        if ($categoryId) {
            $filters['category'] = $categoryId;
        }
        if ($minPrice) {
            $filters['minPrice'] = $minPrice;
        }
        if ($maxPrice) {
            $filters['maxPrice'] = $maxPrice;
        }

        // Get filtered and paginated products
        $products = $productRepository->findByFilters($filters, $sort, $order, $page, $limit);
        $totalProducts = $productRepository->countByFilters($filters);
        $totalPages = ceil($totalProducts / $limit);

        // Get all categories for filter dropdown
        $categories = $categoryRepository->findAll();

        return $this->render('shop/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'currentFilters' => $filters,
            'currentSort' => $sort,
            'currentOrder' => $order,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_detail")
     */
    public function productDetail(int $id, \App\Repository\ProductRepository $productRepository, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Track recently viewed products in session
        $session = $request->getSession();
        $recentlyViewed = $session->get('recently_viewed', []);
        array_unshift($recentlyViewed, $id);
        $recentlyViewed = array_unique($recentlyViewed);
        $recentlyViewed = array_slice($recentlyViewed, 0, 10); // Keep only last 10
        $session->set('recently_viewed', $recentlyViewed);

        // Get related products from same category (excluding current product)
        $relatedProducts = $productRepository->findBy([
            'category' => $product->getCategory(),
            'status' => true
        ], ['createdAt' => 'DESC'], 5);

        // Remove current product from related products
        $relatedProducts = array_filter($relatedProducts, function($p) use ($product) {
            return $p->getId() !== $product->getId();
        });

        // Get approved reviews
        $approvedReviews = array_filter($product->getReviews()->toArray(), function($review) {
            return $review->isApproved();
        });

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'relatedProducts' => array_slice($relatedProducts, 0, 4),
            'reviews' => $approvedReviews
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(\App\Repository\ProductRepository $productRepository, \App\Repository\CategoryRepository $categoryRepository, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        $query = $request->query->get('q', '');
        $categoryId = $request->query->get('category');
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'ASC');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 12; // Products per page

        // Build filters array
        $filters = [];
        if ($categoryId) {
            $filters['category'] = $categoryId;
        }
        if ($minPrice) {
            $filters['minPrice'] = $minPrice;
        }
        if ($maxPrice) {
            $filters['maxPrice'] = $maxPrice;
        }

        // Get filtered and paginated products
        $products = $productRepository->searchByQuery($query, $filters, $sort, $order, $page, $limit);
        $totalProducts = $productRepository->countBySearchQuery($query, $filters);
        $totalPages = ceil($totalProducts / $limit);

        // Get all categories for filter dropdown
        $categories = $categoryRepository->findAll();

        return $this->render('shop/search.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'currentFilters' => $filters,
            'currentSort' => $sort,
            'currentOrder' => $order,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
            'searchQuery' => $query
        ]);
    }

    /**
     * @Route("/search/autocomplete", name="search_autocomplete")
     */
    public function autocomplete(\App\Repository\ProductRepository $productRepository, \Symfony\Component\HttpFoundation\Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $query = $request->query->get('q', '');
        if (strlen($query) < 2) {
            return $this->json([]);
        }

        $suggestions = $productRepository->findAutocompleteSuggestions($query, 10);
        $results = array_map(function($product) {
            return [
                'name' => $product->getName(),
                'url' => $this->generateUrl('product_detail', ['id' => $product->getId()])
            ];
        }, $suggestions);

        return $this->json($results);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\Mailer\MailerInterface $mailer): Response
    {
        $contact = new \App\Entity\Contact();
        $form = $this->createForm(\App\Form\ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            // Send email notification
            $email = (new \Symfony\Component\Mime\Email())
                ->from($contact->getEmail())
                ->to('support@2why.com')
                ->subject('New Contact Form Submission: ' . $contact->getSubject())
                ->text('From: ' . $contact->getFirstName() . ' ' . $contact->getLastName() . "\n\n" . $contact->getMessage());

            $mailer->send($email);

            $this->addFlash('success', 'Thank you for your message! We will get back to you soon.');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
