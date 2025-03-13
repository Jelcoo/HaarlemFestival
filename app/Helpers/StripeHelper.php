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

    public function createIntent(int $amount, int $orderId, ?string $customerId)
    {
        $paymentIntent = [
            'amount' => $amount,
            'currency' => 'eur',
            'metadata' => [
                'order_id' => $orderId,
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

        // Calculate dance events
        if (!empty($items['dance'])) {
            foreach ($items['dance'] as $danceItem) {
                $amount += $danceItem['price'] * $danceItem['quantity'];
            }
        }

        // Calculate yummy (restaurant) reservations
        if (!empty($items['yummy'])) {
            foreach ($items['yummy'] as $yummyItem) {
                $amount += $yummyItem['reservationcost'];
            }
        }

        // Calculate history tours
        if (!empty($items['history'])) {
            foreach ($items['history'] as $historyItem) {
                if ($historyItem['type'] === 'family') {
                    $amount += $historyItem['price'];
                } else {
                    $amount += $historyItem['price'] * $historyItem['seats'];
                }
            }
        }

        return intval(number_format($amount, 2, '', ''));
    }
}
