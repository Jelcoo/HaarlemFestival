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
            case 'payment_intent.created':
                /** @var \Stripe\PaymentIntent $paymentIntent */
                $paymentIntent = $event->data;
                $uploadDir = '/app/storage/';
                $destination = "{$uploadDir}created_intent.json";
                if (file_put_contents($destination, json_encode($paymentIntent))) {
                    http_response_code(200);
                } else {
                    http_response_code(301);
                }
                break;
            case 'payment_intent.succeeded':
                /** @var \Stripe\PaymentIntent $paymentIntent */
                $paymentIntent = $event->data;
                $uploadDir = '/app/storage/';
                $destination = "{$uploadDir}succeeded_intent.json";
                if (file_put_contents($destination, json_encode($paymentIntent))) {
                    http_response_code(200);
                } else {
                    http_response_code(301);
                }
                break;
            case 'payment_intent.payment_failed':
                /** @var \Stripe\PaymentIntent $paymentIntent */
                $paymentIntent = $event->data;
                $uploadDir = '/app/storage/';
                $destination = "{$uploadDir}failed_intent.json";
                if (file_put_contents($destination, json_encode($paymentIntent))) {
                    http_response_code(200);
                } else {
                    http_response_code(301);
                }
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }
    }
}
