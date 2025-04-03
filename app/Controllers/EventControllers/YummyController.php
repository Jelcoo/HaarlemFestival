<?php

namespace App\Controllers\EventControllers;

use App\Controllers\Controller;
use App\Repositories\RestaurantRepository;
use App\Repositories\LocationRepository;
use App\Services\AssetService;

class YummyController extends Controller
{
    private RestaurantRepository $restaurantRepository;
    private LocationRepository $locationRepository;
    private AssetService $assetService;

    public function __construct()
    {
        parent::__construct();
        $this->restaurantRepository = new RestaurantRepository();
        $this->locationRepository = new LocationRepository();
        $this->assetService = new AssetService();
    }

    public function showDetail(string $slug, int $id): string
{
    $restaurant = $this->restaurantRepository->getRestaurantByIdWithLocation($id);
    if (!$restaurant) {
        return $this->pageLoader->setPage('_404')->render();
    }

    $expectedSlug = str_replace(' ', '_', $restaurant->location->name);
    if (urldecode($slug) !== $expectedSlug) {
        header("Location: /yummy/{$expectedSlug}_{$id}", true, 301);
        exit;
    }

    $events = $this->restaurantRepository->getEventsByRestaurantId($id);
    $headerAsset = $this->assetService->resolveAssets($restaurant, 'header');
    $extraAssets = $this->assetService->resolveAssets($restaurant, 'extra');
    $logoAsset = $this->assetService->resolveAssets($restaurant, 'logo');

    function getMostCommonValue(array $values): float
    {
        $counts = array_count_values(array_map(fn($v) => number_format((float)$v, 2, '.', ''), $values));
        arsort($counts);
        return (float) array_key_first($counts);
    }

    $adultPrices = array_map(fn($e) => $e->adult_price, $events);
    $kidsPrices = array_map(fn($e) => $e->kids_price, $events);

    $mostCommonAdultPrice = getMostCommonValue($adultPrices);
    $mostCommonKidsPrice = getMostCommonValue($kidsPrices);

    $hasPriceVariation = count(array_unique($adultPrices)) > 1 || count(array_unique($kidsPrices)) > 1;

    return $this->pageLoader->setPage('restaurant-detail')->render([
        'restaurant' => $restaurant,
        'events' => $events,
        'headerAsset' => $headerAsset,
        'logoAsset' => $logoAsset,
        'extraAssets' => $extraAssets,
        'adult_price' => $mostCommonAdultPrice,
        'kids_price' => $mostCommonKidsPrice,
        'has_price_variation' => $hasPriceVariation,
    ]);
    
}


    public function showMain(): string
    {
        $restaurants = $this->locationRepository->getYummyLocations();

        return $this->pageLoader->setPage('yummy')->render([
            'restaurants' => $restaurants,
        ]);
    }
}
