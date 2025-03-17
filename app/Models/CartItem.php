<?php

namespace App\Models;

class CartItem
{
    public int $id;
    public int $cart_id;
    public string $event_model;
    public int $event_id;
    public int $quantity;
    public ?string $note;
    public ?Event $event;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->cart_id = $collection['cart_id'];
        $this->event_model = $collection['event_model'];
        $this->event_id = $collection['event_id'];
        $this->quantity = $collection['quantity'];
        $this->note = $collection['note'];
    }
}
