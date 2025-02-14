<?php

namespace App\Controllers;
use App\Application\Response;
use App\Config\Config;
use App\Controllers\Controller;
use Error;
use Exception;
use Stripe\StripeClient;

class StripeController extends Controller
{
    private StripeClient $stripe;
    public function __construct()
    {
        parent::__construct();
        $this->stripe = new StripeClient(Config::getKey("STRIPE_SECRET_KEY"));
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
        function calculateOrderAmount(array $items): int
        {
            // Calculate the order total on the server to prevent
            // people from directly manipulating the amount on the client
            $total = 0;
            foreach ($items as $item) {
                $total += $item->amount;
            }
            return $total;
        }
        try {
            // retrieve JSON from POST body
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            // Create a PaymentIntent with amount and currency
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => calculateOrderAmount($jsonObj->items),
                'currency' => 'eur',
                'metadata' => [
                    'invoice_id' => 1
                ],
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            return json_encode($output);
        } catch (Error $e) {
            $response = new Response();
            $response->setStatusCode(500);
            $response->setContent($e->getMessage());
            $response->sendJson();
            exit;
        }


    }
}