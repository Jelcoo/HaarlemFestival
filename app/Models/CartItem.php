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
    public EventDance|EventHistory|EventYummy|null $event;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->cart_id = $collection['cart_id'];
        $this->event_model = $collection['event_model'];
        $this->event_id = $collection['event_id'];
        $this->quantity = $collection['quantity'];
        $this->note = $collection['note'];
    }

    public function basePrice(): float
    {
        switch ($this->event_model) {
            case EventDance::class:
                return $this->event->price;
            case EventHistory::class:
                return $this->event->single_price;
            case EventYummy::class:
                return $this->event->adult_price;
            default:
                return 0;
        }
    }

    public function singlePrice(): float
    {
        return round($this->basePrice() * ($this->event->vat + 1), 2);
    }
    
    public function totalPrice(): float
    {
        return round($this->singlePrice() * $this->quantity, 2);
    }
}
