<?php

namespace App\Models;

use App\Enum\ItemQuantityEnum;

class CartItemQuantity
{
    public int $id;
    public int $cart_item_id;
    public ItemQuantityEnum $type;
    public int $quantity;

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
        $this->cart_item_id = $collection['cart_item_id'];
        $this->type = ItemQuantityEnum::from($collection['type']);
        $this->quantity = $collection['quantity'];
    }
}
