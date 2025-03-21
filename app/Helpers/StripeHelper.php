<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Config\Config;
use Stripe\StripeClient;

class StripeHelper
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(Config::getKey('STRIPE_SECRET_KEY', 'abc'));
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

        return $paymentIntent->client_secret;
    }

    public function createCustomer(string $email, string $name)
    {
        $customer = $this->stripe->customers->create([
            'email' => $email,
            'name' => $name,
        ]);

        return $customer->id;
    }

    public static function calculateOrderAmount(Cart $cart)
    {
        $total = array_reduce($cart->items, function ($carry, $item) {
            return $carry + $item->totalPrice();
        }, 0);

        return intval(number_format($total, 2, '', ''));
    }

    public function retrievePaymentIntent(string $paymentIntentId)
    {
        return $this->stripe->paymentIntents->retrieve($paymentIntentId);
    }
}
