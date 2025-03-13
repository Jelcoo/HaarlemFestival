<?php

namespace App\Models\DTO;

class RestaurantInformation
{
    public $event_id;
    public $restaurant_name;
    public $restaurant_address;
    public $restaurant_phone;
    public $restaurant_website;
    public $location;
    public $start_date;
    public $start_time;
    public $kids_price;
    public $adult_price;
    public $vat;

    public function __construct(array $data)
    {
        $this->event_id = $data['event_id'];
        $this->restaurant_name = $data['restaurant_name'];
        $this->location = $data['location'];
        $this->start_date = $data['start_date'];
        $this->start_time = $data['start_time'];
        $this->kids_price = $data['kids_price'];
        $this->adult_price = $data['adult_price'];
        $this->vat = $data['vat'];
    }
}
