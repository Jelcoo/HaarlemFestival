<?php

namespace App\Controllers;

use App\Config\Config;
use App\Application\Response;
use App\Helpers\StripeHelper;
use App\Services\CartService;
use App\Services\OrderService;
use App\Repositories\OrderRepository;

class CheckoutController extends Controller
{
    private StripeHelper $stripe;
    private OrderService $orderService;
    private CartService $cartService;
    private OrderRepository $orderRepository;

    public function __construct()
    {
        parent::__construct();
        $this->stripe = new StripeHelper();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->orderRepository = new OrderRepository();
    }

    public function index(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart(true, true);

        return $this->pageLoader->setPage('checkout/index')->render([
            'cartItems' => $cart->items,
        ] + $paramaters);
    }

    public function checkout(array $paramaters = [])
    {
        return $this->pageLoader->setPage('checkout/pay')->render($paramaters);
    }

    public function completePayment(array $paramaters = [])
    {
        return $this->pageLoader->setPage('checkout/complete')->render($paramaters);
    }

    public function payLater(array $paramaters = [])
    {
        return $this->pageLoader->setPage('checkout/pay_later')->render($paramaters);
    }

    public function createCheckout()
    {
        try {
            // retrieve JSON from POST body
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr, true);
            $amount = StripeHelper::calculateOrderAmount($jsonObj);
            if ($amount == 0) {
                $response = new Response();
                $response->setStatusCode(400);
                $response->setContent('No order items found');
                $response->sendJson();
                exit;
            }

            $invoiceId = $this->orderService->createOrder($jsonStr);

            $clientSecret = $this->stripe->createIntent($amount, $invoiceId, $this->getAuthUser()->stripe_customer_id);

            return json_encode($clientSecret);
        } catch (\Error $e) {
            $response = new Response();
            $response->setStatusCode(500);
            $response->setContent($e->getMessage());
            $response->sendJson();
            exit;
        }
    }

    public function webhook()
    {
        $payload = file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $_SERVER['HTTP_STRIPE_SIGNATURE'],
                Config::getKey('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent($e->getMessage());
            $response->sendJson();
            exit;
        }

        switch ($event->type) {
            case 'payment_intent.created':
                /** @var \Stripe\PaymentIntent $paymentIntent */
                $paymentIntent = $event->data->object;
                try {
                    $this->orderRepository->setStripeId(intval($paymentIntent->metadata->order_id), $paymentIntent->id);
                    $this->orderRepository->updateOrderStatus(intval($paymentIntent->metadata->order_id), 'pending');
                    http_response_code(200);
                } catch (\Exception $e) {
                    http_response_code(500);
                }
                break;
            case 'payment_intent.succeeded':
                /** @var \Stripe\PaymentIntent $paymentIntent */
                $paymentIntent = $event->data->object;
                $this->orderRepository->updateOrderStatus(intval($paymentIntent->metadata->order_id), 'completed');
                $this->orderRepository->completeOrder(intval($paymentIntent->metadata->order_id));

                http_response_code(200);
                break;
            case 'payment_intent.payment_failed':
                /** @var \Stripe\PaymentIntent $paymentIntent */
                $paymentIntent = $event->data->object;
                $this->orderRepository->updateOrderStatus(intval($paymentIntent->metadata->order_id), 'failed');
                http_response_code(200);
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }
    }
}
