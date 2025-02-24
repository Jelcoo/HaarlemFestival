<?php

namespace App\Controllers;

use App\Config\Config;
use App\Application\Response;
use App\Helpers\StripeHelper;

class StripeController extends Controller
{
    private StripeHelper $stripe;

    public function __construct()
    {
        parent::__construct();
        $this->stripe = new StripeHelper();
    }

    public function index()
    {
        return $this->pageLoader->setPage('checkout')->render();
    }

    public function cart()
    {
        return $this->pageLoader->setPage('cart')->render();
    }

    public function complete()
    {
        return $this->pageLoader->setPage('complete')->render();
    }

    public function create()
    {

        try {
            // retrieve JSON from POST body
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr, true);
            $amount = StripeHelper::calculateOrderAmount($jsonObj['items']);
            $clientSecret = $this->stripe->createIntent($amount, null);

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
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                $uploadDir = '/app/storage/';
                $destination = "{$uploadDir}succeeded_intent.json";
                if (file_put_contents($destination, json_encode($paymentIntent))) {
                    http_response_code(200);
                } else {
                    http_response_code(301);
                }
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                $uploadDir = '/app/storage/';
                $destination = "{$uploadDir}failed_intent.json";
                if (file_put_contents($destination, json_encode($paymentIntent))) {
                    http_response_code(200);
                } else {
                    http_response_code(301);
                }
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                break;
                // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }
    }
}
