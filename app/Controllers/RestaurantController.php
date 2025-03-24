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

    public function show(string $slug, int $id): string
    {
        $restaurant = $this->restaurantRepository->getRestaurantWithLocationById($id);
        if (!$restaurant) {
            return $this->pageLoader->setPage('_404')->render();
        }
    
        // Expected slug based on name (does not handle special characters)
        $expectedSlug = str_replace(' ', '_', $restaurant->location->name) ;
    
        // Redirect if incorrect URL
        if (urldecode($slug) !== $expectedSlug) {
            header("Location: /yummy/{$expectedSlug}_{$id}", true, 301);
            exit;
        }
    
        return $this->pageLoader->setPage('restaurant-detail')->render([
            'restaurant' => $restaurant
        ]);
    }
    
}
