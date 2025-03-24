<?php

namespace App\Controllers;

use App\Config\Config;
use App\Application\Response;
use App\Helpers\StripeHelper;
use App\Services\CartService;
use App\Services\OrderService;
use App\Enum\InvoiceStatusEnum;
use Rakit\Validation\Validator;
use App\Repositories\CartRepository;
use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;
use App\Adapters\InvoiceToCartAdapter;
use App\Repositories\InvoiceRepository;

class CheckoutController extends Controller
{
    private StripeHelper $stripe;
    private OrderService $orderService;
    private CartService $cartService;
    private OrderRepository $orderRepository;
    private CartRepository $cartRepository;
    private InvoiceToCartAdapter $invoiceToCartAdapter;
    private InvoiceRepository $invoiceRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->stripe = new StripeHelper();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->orderRepository = new OrderRepository();
        $this->cartRepository = new CartRepository();
        $this->invoiceToCartAdapter = new InvoiceToCartAdapter();
        $this->invoiceRepository = new InvoiceRepository();
        $this->userRepository = new UserRepository();
    }

    public function index(array $paramaters = [])
    {
        if (!isset($_GET['id']) && !isset($paramaters['fields']['id'])) {
            $cart = $this->cartService->getSessionCart(true, true);
        } else {
            if ($this->invoiceRepository->isPayableInvoice($_GET['id'] ?? $paramaters['fields']['id'], $this->getAuthUser()->id)) {
                $cart = $this->invoiceToCartAdapter->adapt($_GET['id'] ?? $paramaters['fields']['id']);
            } else {
                Response::redirect('/');
            }
        }

        $user = $this->getAuthUser();

        return $this->pageLoader->setPage('checkout/index')->render([
            'cartItems' => $cart->items,
            'user' => $user,
        ] + $paramaters);
    }

    public function checkout(array $paramaters = [])
    {
        $validator = new Validator();

        $rules = [
            'phone_number' => 'required|max:255',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'postal_code' => 'required|max:255',
        ];

        $validation = $validator->validate($_POST, $rules);

        if ($validation->fails()) {
            return $this->index([
                'error' => $validation->errors()->toArray(),
                'fields' => $_POST,
            ]);
        }

        try {
            $user = $this->getAuthUser();
            $user->phone_number = $_POST['phone_number'];
            $user->address = $_POST['address'];
            $user->city = $_POST['city'];
            $user->postal_code = $_POST['postal_code'];

            $this->userRepository->updateUser($user);
        } catch (\Exception $e) {
            return $this->index([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        $secret = $this->createCheckout($_POST['id'] ?? null);

        return $this->pageLoader->setPage('checkout/pay')->render([
            'clientSecret' => $secret,
        ] + $paramaters);
    }

    public function completePayment(array $paramaters = [])
    {
        $paymentIntent = $this->stripe->retrievePaymentIntent($_GET['payment_intent']);

        return $this->pageLoader->setPage('checkout/complete')->render([
            'status' => $paymentIntent->status,
        ] + $paramaters);
    }

    public function payLater(array $paramaters = [])
    {
        return $this->pageLoader->setPage('checkout/pay_later')->render($paramaters);
    }

    private function createCheckout(?int $invoiceId = null)
    {
        try {
            $amount = 0;

            if (is_null($invoiceId)) {
                $cart = $this->cartService->getSessionCart(true, true);
            } else {
                $cart = $this->invoiceToCartAdapter->adapt($invoiceId);
            }

            $amount = StripeHelper::calculateOrderAmount($cart);
            if ($amount == 0) {
                $response = new Response();
                $response->setStatusCode(400);
                $response->setContent('No order items found');
                $response->sendJson();
                exit;
            }

            if (is_null($invoiceId)) {
                $invoiceId = $this->orderService->createOrder($cart);
                $this->cartRepository->deleteCart($cart->id);
            }

            $clientSecret = $this->stripe->createIntent($amount, $invoiceId, $this->getAuthUser()->stripe_customer_id);

            return $clientSecret;
        } catch (\Error $e) {
            $response = new Response();
            $response->setStatusCode(500);
            $response->setContent((new ErrorController())->error500($e->getMessage()));
            $response->send();
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
                    $this->orderRepository->updateOrderStatus(intval($paymentIntent->metadata->order_id), InvoiceStatusEnum::PENDING);
                    http_response_code(200);
                } catch (\Exception $e) {
                    http_response_code(500);
                }
                break;
            case 'payment_intent.succeeded':
                /** @var \Stripe\PaymentIntent $paymentIntent */
                $paymentIntent = $event->data->object;
                $this->orderRepository->updateOrderStatus(intval($paymentIntent->metadata->order_id), InvoiceStatusEnum::COMPLETED);
                $this->orderRepository->completeOrder(intval($paymentIntent->metadata->order_id));

                http_response_code(200);
                break;
            case 'payment_intent.payment_failed':
                /** @var \Stripe\PaymentIntent $paymentIntent */
                $paymentIntent = $event->data->object;
                $this->orderRepository->updateOrderStatus(intval($paymentIntent->metadata->order_id), InvoiceStatusEnum::FAILED);
                http_response_code(200);
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }
    }
}
