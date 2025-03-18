<?php

namespace App\Models;

use App\Enum\ItemQuantityEnum;

class CartItem
{
    public int $id;
    public int $cart_id;
    public string $event_model;
    public int $event_id;
    public ?string $note;
    public array $quantities = [];
    public EventDance|EventHistory|EventYummy|null $event;

    public function __construct()
    {
        $arguments = func_get_args();

        if (!empty($arguments)) {
            $this->fill($arguments[0]);
        }
    }

    public function fill(array $collection)
    {
        $this->id = $collection['id'];
        $this->cart_id = $collection['cart_id'];
        $this->event_model = $collection['event_model'];
        $this->event_id = $collection['event_id'];
        $this->note = $collection['note'];
    }

    public function basePrice(): float
    {
        switch ($this->event_model) {
            case EventDance::class:
                return $this->event->price;
            case EventYummy::class:
                return $this->event->reservation_cost;
            case EventHistory::class:
                if ($this->quantities[0]->type === ItemQuantityEnum::FAMILY) {
                    return $this->event->family_price;
                }

                return $this->event->single_price;
            default:
                return 0;
        }
    }

    public function quantity(): int
    {
        $totalQuantity = 0;

        foreach ($this->quantities as $quantity) {
            $totalQuantity += $quantity->quantity;
        }

        return $totalQuantity;
    }

    public function singlePrice(): float
    {
        return round($this->basePrice() * ($this->event->vat + 1), 2);
    }

    public function totalPrice(): float
    {
        return round($this->singlePrice() * $this->quantity(), 2);
    }
}
