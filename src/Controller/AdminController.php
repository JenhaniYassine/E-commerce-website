<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\SupportTicket;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use App\Repository\SupportTicketRepository;
use App\Repository\UserRepository;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ProductRepository $productRepository, UserRepository $userRepository, OrderRepository $orderRepository, SettingsRepository $settingsRepository): Response
    {
        $products = $productRepository->findAll();
        $users = $userRepository->findAll();
        $orders = $orderRepository->findAllOrderedByDate();
        $settings = $settingsRepository->getSettings();

        // Calculate stats
        $totalRevenue = 0;
        $pendingOrders = 0;
        foreach ($orders as $order) {
            $totalRevenue += $order->getTotal();
            if ($order->getStatus() === 'pending') {
                $pendingOrders++;
            }
        }

        $stats = [
            'total_products' => count($products),
            'total_orders' => count($orders),
            'pending_orders' => $pendingOrders,
            'total_users' => count($users),
            'revenue' => $totalRevenue,
            'weekly_sales' => 0.00, // TODO: Implement weekly sales
            'monthly_sales' => 0.00 // TODO: Implement monthly sales
        ];

        // Recent activities: last 5 products added
        $recentProducts = $productRepository->findBy([], ['id' => 'DESC'], 5);

        return $this->render('admin/index.html.twig', [
            'stats' => $stats,
            'recent_products' => $recentProducts,
            'products' => $products,
            'orders' => $orders,
            'users' => $users,
            'settings' => $settings
        ]);
    }

    /**
     * @Route("/admin/products", name="admin_products")
     */
    public function products(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('admin/products/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/products/new", name="admin_products_new")
     */
    public function newProduct(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/products/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/products/{id}/edit", name="admin_products_edit")
     */
    public function editProduct(Request $request, int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/products/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    /**
     * @Route("/admin/products/{id}/delete", name="admin_products_delete", methods={"POST"})
     */
    public function deleteProduct(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            // Prevent deletion when product is referenced by order items
            if (count($product->getOrderItems()) > 0) {
                $this->addFlash('error', 'Cannot delete product: it is associated with existing orders.');
            } else {
                $entityManager->remove($product);
                $entityManager->flush();
                $this->addFlash('success', 'Product deleted successfully.');
            }
        }

        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/admin/products/{id}/toggle", name="admin_products_toggle", methods={"POST"})
     */
    public function toggleProduct(int $id, Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        if ($this->isCsrfTokenValid('toggle'.$product->getId(), $request->request->get('_token'))) {
            $product->setStatus(!$product->isStatus());
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/admin/orders", name="admin_orders")
     */
    public function orders(Request $request, OrderRepository $orderRepository): Response
    {
        $status = $request->query->get('status');
        if ($status) {
            $orders = $orderRepository->findByStatus($status);
        } else {
            $orders = $orderRepository->findAllOrderedByDate();
        }

        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders,
            'current_status' => $status
        ]);
    }

    /**
     * @Route("/admin/orders/{id}", name="admin_orders_show")
     */
    public function showOrder(int $id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }
        
        return $this->render('admin/orders/show.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * @Route("/admin/orders/{id}/status", name="admin_orders_update_status", methods={"POST"})
     */
    public function updateOrderStatus(int $id, Request $request, OrderRepository $orderRepository, EntityManagerInterface $entityManager): Response
    {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }
        
        $status = $request->request->get('status');
        if ($this->isCsrfTokenValid('update_status'.$order->getId(), $request->request->get('_token'))) {
            $order->setStatus($status);
            $entityManager->flush();
            $this->addFlash('success', 'Order status updated successfully.');
        }

        return $this->redirectToRoute('admin_orders_show', ['id' => $order->getId()]);
    }

    /**
     * @Route("/admin/orders/{id}/invoice", name="admin_orders_invoice")
     */
    public function downloadInvoice(int $id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        $html = $this->renderView('admin/orders/invoice.html.twig', [
            'order' => $order
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="invoice_' . $order->getId() . '.pdf"'
            ]
        );
    }

    /**
     * @Route("/admin/orders/{id}/contact", name="admin_orders_contact", methods={"POST"})
     */
    public function contactCustomer(int $id, Request $request, OrderRepository $orderRepository, MailerInterface $mailer): Response
    {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }
        
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');

        if ($this->isCsrfTokenValid('contact'.$order->getId(), $request->request->get('_token'))) {
            $email = (new Email())
                ->from('admin@example.com')
                ->to($order->getUser()->getEmail())
                ->subject($subject)
                ->text($message);

            $mailer->send($email);
            $this->addFlash('success', 'Email sent to customer successfully.');
        }

        return $this->redirectToRoute('admin_orders_show', ['id' => $order->getId()]);
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function users(Request $request, UserRepository $userRepository): Response
    {
        $search = $request->query->get('search');
        if ($search) {
            $users = $userRepository->searchByNameOrEmail($search);
        } else {
            $users = $userRepository->findAll();
        }

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
            'search' => $search
        ]);
    }

    /**
     * @Route("/admin/users/{id}", name="admin_users_show")
     */
    public function showUser(int $id, UserRepository $userRepository): Response
    {
        $userEntity = $userRepository->find($id);
        
        if (!$userEntity) {
            throw $this->createNotFoundException('User not found');
        }
        
        return $this->render('admin/users/show.html.twig', [
            'user' => $userEntity
        ]);
    }

    /**
     * @Route("/admin/users/{id}/ban", name="admin_users_ban", methods={"POST"})
     */
    public function banUser(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $userEntity = $userRepository->find($id);
        
        if (!$userEntity) {
            throw $this->createNotFoundException('User not found');
        }
        
        if ($this->isCsrfTokenValid('ban'.$userEntity->getId(), $request->request->get('_token'))) {
            $userEntity->setBanned(true);
            $entityManager->flush();
            $this->addFlash('success', 'User banned successfully.');
        }

        return $this->redirectToRoute('admin_users_show', ['id' => $userEntity->getId()]);
    }

    /**
     * @Route("/admin/users/{id}/unban", name="admin_users_unban", methods={"POST"})
     */
    public function unbanUser(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $userEntity = $userRepository->find($id);
        
        if (!$userEntity) {
            throw $this->createNotFoundException('User not found');
        }
        
        if ($this->isCsrfTokenValid('unban'.$userEntity->getId(), $request->request->get('_token'))) {
            $userEntity->setBanned(false);
            $entityManager->flush();
            $this->addFlash('success', 'User unbanned successfully.');
        }

        return $this->redirectToRoute('admin_users_show', ['id' => $userEntity->getId()]);
    }

    /**
     * @Route("/admin/reviews", name="admin_reviews")
     */
    public function reviews(Request $request, ReviewRepository $reviewRepository): Response
    {
        $status = $request->query->get('status');
        if ($status === 'approved') {
            $reviews = $reviewRepository->findApproved();
        } elseif ($status === 'pending') {
            $reviews = $reviewRepository->findPending();
        } else {
            $reviews = $reviewRepository->findAll();
        }

        return $this->render('admin/reviews/index.html.twig', [
            'reviews' => $reviews,
            'current_status' => $status
        ]);
    }

    /**
     * @Route("/admin/reviews/{id}/approve", name="admin_reviews_approve", methods={"POST"})
     */
    public function approveReview(Review $review, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('approve'.$review->getId(), $request->request->get('_token'))) {
            $review->setApproved(true);
            $entityManager->flush();
            $this->addFlash('success', 'Review approved successfully.');
        }

        return $this->redirectToRoute('admin_reviews');
    }

    /**
     * @Route("/admin/reviews/{id}/delete", name="admin_reviews_delete", methods={"POST"})
     */
    public function deleteReview(Review $review, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_review'.$review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
            $this->addFlash('success', 'Review deleted successfully.');
        }

        return $this->redirectToRoute('admin_reviews');
    }

    /**
     * @Route("/admin/support", name="admin_support")
     */
    public function supportTickets(Request $request, SupportTicketRepository $supportTicketRepository): Response
    {
        $status = $request->query->get('status');
        if ($status) {
            $tickets = $supportTicketRepository->findByStatus($status);
        } else {
            $tickets = $supportTicketRepository->findAllOrderedByDate();
        }

        return $this->render('admin/support/index.html.twig', [
            'tickets' => $tickets,
            'current_status' => $status
        ]);
    }

    /**
     * @Route("/admin/support/{id}/status", name="admin_support_update_status", methods={"POST"})
     */
    public function updateTicketStatus(SupportTicket $ticket, Request $request, EntityManagerInterface $entityManager): Response
    {
        $status = $request->request->get('status');
        if ($this->isCsrfTokenValid('update_status_ticket'.$ticket->getId(), $request->request->get('_token'))) {
            $ticket->setStatus($status);
            $entityManager->flush();
            $this->addFlash('success', 'Ticket status updated successfully.');
        }

        return $this->redirectToRoute('admin_support');
    }

    /**
     * @Route("/admin/analytics", name="admin_analytics")
     */
    public function analytics(OrderRepository $orderRepository, UserRepository $userRepository): Response
    {
        // Total revenue and orders
        $orders = $orderRepository->findAll();
        $totalRevenue = 0;
        $totalOrders = count($orders);
        foreach ($orders as $order) {
            $totalRevenue += $order->getTotal();
        }

        // Conversion rate (orders / users)
        $totalUsers = count($userRepository->findAll());
        $conversionRate = $totalUsers > 0 ? round(($totalOrders / $totalUsers) * 100, 2) : 0;

        // Average order value
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Monthly sales data (last 12 months)
        $salesData = [];
        $salesLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = new \DateTime();
            $date->modify("-{$i} months");
            $month = $date->format('M Y');
            $salesLabels[] = $month;

            $monthlyRevenue = 0;
            foreach ($orders as $order) {
                if ($order->getCreatedAt()->format('M Y') === $month) {
                    $monthlyRevenue += $order->getTotal();
                }
            }
            $salesData[] = $monthlyRevenue;
        }

        // Top products (aggregated from OrderItem)
        $topProducts = [];
        $productSales = [];
        foreach ($orders as $order) {
            foreach ($order->getOrderItems() as $item) {
                $productId = $item->getProduct()->getId();
                if (!isset($productSales[$productId])) {
                    $productSales[$productId] = [
                        'name' => $item->getProduct()->getName(),
                        'quantity' => 0,
                        'revenue' => 0
                    ];
                }
                $productSales[$productId]['quantity'] += $item->getQuantity();
                $productSales[$productId]['revenue'] += $item->getPrice() * $item->getQuantity();
            }
        }
        arsort($productSales);
        $topProducts = array_slice($productSales, 0, 5);

        // Recent orders (last 5)
        $recentOrders = $orderRepository->findBy([], ['createdAt' => 'DESC'], 5);

        // Prepare data for charts
        $productsLabels = array_keys($topProducts);
        $productsData = array_column($topProducts, 'quantity');

        return $this->render('admin/analytics/index.html.twig', [
            'analytics' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'conversion_rate' => $conversionRate,
                'avg_order_value' => $avgOrderValue,
                'sales_labels' => $salesLabels,
                'sales_data' => $salesData,
                'products_labels' => $productsLabels,
                'products_data' => $productsData,
                'top_products' => $topProducts,
                'recent_orders' => $recentOrders
            ]
        ]);
    }

    /**
     * @Route("/admin/settings", name="admin_settings")
     */
    public function settings(Request $request, SettingsRepository $settingsRepository, EntityManagerInterface $entityManager): Response
    {
        $settings = $settingsRepository->getSettings();
        if (!$settings) {
            $settings = new \App\Entity\Settings();
        }

        $form = $this->createForm(\App\Form\SettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

                try {
                    $logoFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/images',
                        $newFilename
                    );
                    $settings->setLogo('/uploads/images/'.$newFilename);
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }
            }

            $settingsRepository->add($settings, true);
            $this->addFlash('success', 'Settings updated successfully.');

            return $this->redirectToRoute('admin_settings');
        }

        return $this->render('admin/settings/index.html.twig', [
            'form' => $form->createView(),
            'settings' => $settings
        ]);
    }
}
