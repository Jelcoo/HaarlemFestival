<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\OrderRepository;

class OrderService
{
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function validateAvailability(Cart $cart): array
    {
        $cartItems = $cart->items;

        $errors = [
            'dance' => [],
            'yummy' => [],
            'history' => [],
        ];

        foreach ($cartItems as $item) {
            switch ($item->event_model) {
                case 'App\\Models\\EventDance':
                    $quantity = $item->quantities[0];
                    $availability = $this->orderRepository->checkDanceTicketAvailable($item->event_id, $quantity->quantity, $quantity->type);

                    if (!$availability) {
                        $errors['dance'][$item->event_id] = 'Ticket not available';
                    }
                    break;
                case 'App\\Models\\EventYummy':
                    $childrenQuantity = 0;
                    $adultQuantity = 0;
                    foreach ($item->quantities as $quantity) {
                        if ($quantity->type->value === 'child') {
                            $childrenQuantity += $quantity->quantity;
                        } else {
                            $adultQuantity += $quantity->quantity;
                        }
                    }

                    $availability = $this->orderRepository->checkYummyTicketAvailable($item->event_id, $childrenQuantity, $adultQuantity);

                    if (!$availability) {
                        $errors['yummy'][$item->event_id] = 'Ticket not available';
                    }
                    break;
                case 'App\\Models\\EventHistory':
                    $quantity = $item->quantities[0]->quantity;
                    $availability = $this->orderRepository->checkHistoryTicketAvailable($item->event_id, $quantity);

                    if (!$availability) {
                        $errors['history'][$item->event_id] = 'Ticket not available';
                    }
                    break;
            }
        }

        return $errors;
    }

    public function createOrder(Cart $cart)
    {
        return $this->orderRepository->createOrder($cart);
    }
}
