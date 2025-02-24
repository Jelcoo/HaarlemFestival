<?php

namespace App\Helpers;

use App\Config\Config;
use Stripe\StripeClient;

class StripeHelper
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(Config::getKey('STRIPE_SECRET_KEY'));
    }

    public function createIntent(int $amount, ?string $customerId)
    {
        $paymentIntent = [
            'amount' => $amount,
            'currency' => 'eur',
            'metadata' => [
                'order_id' => 1,
            ],
        ];

        if ($customerId) {
            $paymentIntent['customer'] = $customerId;
        }

        $paymentIntent = $this->stripe->paymentIntents->create($paymentIntent);

        $clientSecret = [
            'clientSecret' => $paymentIntent->client_secret,
        ];

        return $clientSecret;
    }

    public function createCustomer(string $email, string $name)
    {
        $customer = $this->stripe->customers->create([
            'email' => $email,
            'name' => $name,
        ]);

        return $customer->id;
    }

    public static function calculateOrderAmount(array $items)
    {
        $amount = 0;

        foreach ($items as $item) {
            $amount += $item['price'] * $item['quantity'];
        }

        return intval(number_format($amount, 2, '', ''));
    }
}