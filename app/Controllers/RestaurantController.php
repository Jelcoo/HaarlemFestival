<?php

namespace App\Controllers;

use App\Repositories\RestaurantRepository;

class RestaurantController extends Controller
{
    private RestaurantRepository $restaurantRepository;

    public function __construct()
    {
        parent::__construct();
        $this->restaurantRepository = new RestaurantRepository();
    }

    public function show(int $id): string
    {
        $restaurant = $this->restaurantRepository->getRestaurantWithLocationById($id);
        if (!$restaurant) {
            return $this->pageLoader->setPage('_404')->render();
        }
        

        return $this->pageLoader->setPage('restaurant-detail')->render([
            'restaurant' => $restaurant
        ]);
    }                                                               
}
