<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Repository\ProductRepository;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="cart_index")
     */
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $cart = $request->getSession()->get('cart', []);

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $total = $product->getPrice() * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $total
                ];
                $subtotal += $total;
            }
        }

        // Simple shipping estimation
        $shipping = $subtotal > 50 ? 0 : 5.99;
        $total = $subtotal + $shipping;

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }

    /**
     * @Route("/add/{id}", name="cart_add", methods={"POST"})
     */
    public function add(
        int $id,
        Request $request,
        ProductRepository $productRepository,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Validate CSRF token
        $token = $request->request->get('_token');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('cart_add', $token))) {
            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => false, 'message' => 'Invalid CSRF token'], 400);
            }
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $quantity = $request->request->getInt('quantity', 1);
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id] += $quantity;
        } else {
            $cart[$id] = $quantity;
        }

        // Ensure we don't exceed stock
        if ($cart[$id] > $product->getStockQuantity()) {
            $cart[$id] = $product->getStockQuantity();
        }

        $session->set('cart', $cart);

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
                'cartCount' => array_sum($cart),
                'message' => 'Product added to cart'
            ]);
        }

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/update/{id}", name="cart_update", methods={"POST"})
     */
    public function update(int $id, Request $request, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $quantity = $request->request->getInt('quantity', 0);
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = min($quantity, $product->getStockQuantity());
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/remove/{id}", name="cart_remove")
     */
    public function remove(int $id, Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        unset($cart[$id]);
        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/count", name="cart_count")
     */
    public function count(Request $request): Response
    {
        $cart = $request->getSession()->get('cart', []);
        return $this->json(['count' => array_sum($cart)]);
    }
}
