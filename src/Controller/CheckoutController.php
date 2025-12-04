<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @Route("/checkout")
 */
#[IsGranted('ROLE_USER')]
class CheckoutController extends AbstractController
{
    /**
     * @Route("/", name="checkout_address")
     */
    public function address(Request $request): Response
    {
        // Check if cart is empty
        $cart = $request->getSession()->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('warning', 'Your cart is empty.');
            return $this->redirectToRoute('cart_index');
        }

        // Get user's saved addresses
        $user = $this->getUser();
        $addresses = $user->getAddresses();

        return $this->render('checkout/address.html.twig', [
            'addresses' => $addresses,
        ]);
    }

    /**
     * @Route("/payment", name="checkout_payment", methods={"POST"})
     */
    public function payment(Request $request, ProductRepository $productRepository): Response
    {
        $cart = $request->getSession()->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('warning', 'Your cart is empty.');
            return $this->redirectToRoute('cart_index');
        }

        // Calculate totals
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

        $shipping = $subtotal > 50 ? 0 : 5.99;
        $total = $subtotal + $shipping;

        // Get address data from form
        $billingAddress = $request->request->get('billing_address');
        $shippingAddress = $request->request->get('shipping_address');

        // Store in session for next step
        $request->getSession()->set('checkout_addresses', [
            'billing' => $billingAddress,
            'shipping' => $shippingAddress
        ]);

        return $this->render('checkout/payment.html.twig', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/process", name="checkout_process", methods={"POST"})
     */
    public function process(Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $cart = $request->getSession()->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('warning', 'Your cart is empty.');
            return $this->redirectToRoute('cart_index');
        }

        $addresses = $request->getSession()->get('checkout_addresses');
        if (!$addresses) {
            $this->addFlash('error', 'Address information is missing.');
            return $this->redirectToRoute('checkout_address');
        }

        $paymentMethod = $request->request->get('payment_method');
        if (!$paymentMethod) {
            $this->addFlash('error', 'Please select a payment method.');
            return $this->redirectToRoute('checkout_payment');
        }

        // Create order
        $user = $this->getUser();
        $order = new Order();
        $order->setUser($user);
        $order->setStatus('Processing');

        $subtotal = 0;
        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $orderItem = new OrderItem();
                $orderItem->setProduct($product);
                $orderItem->setQuantity($quantity);
                $orderItem->setPrice($product->getPrice());
                $order->addOrderItem($orderItem);

                $subtotal += $product->getPrice() * $quantity;
            }
        }

        $shipping = $subtotal > 50 ? 0 : 5.99;
        $total = $subtotal + $shipping;
        $order->setTotal($total);

        $entityManager->persist($order);
        $entityManager->flush();

        // Clear cart
        $request->getSession()->remove('cart');
        $request->getSession()->remove('checkout_addresses');

        // Simulate payment processing
        // In a real app, integrate with payment gateway here
        $this->addFlash('success', 'Order placed successfully! Order #' . $order->getId());

        return $this->redirectToRoute('checkout_confirmation', ['id' => $order->getId()]);
    }

    /**
     * @Route("/confirmation/{id}", name="checkout_confirmation")
     */
    public function confirmation(int $id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }
        
        // Ensure user can only view their own orders
        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('checkout/confirmation.html.twig', [
            'order' => $order,
        ]);
    }
}
