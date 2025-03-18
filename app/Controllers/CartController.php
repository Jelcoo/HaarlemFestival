<?php

namespace App\Controllers;

use App\Models\CartItem;
use App\Models\EventDance;
use App\Models\EventYummy;
use App\Models\EventHistory;
use App\Application\Response;
use App\Services\CartService;
use App\Enum\ItemQuantityEnum;
use App\Services\OrderService;
use App\Models\CartItemQuantity;
use App\Repositories\CartRepository;

class CartController extends Controller
{
    private OrderService $orderService;
    private CartService $cartService;
    private CartRepository $cartRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->cartRepository = new CartRepository();
    }

    public function index(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart(true, true);

        return $this->pageLoader->setPage('cart/index')->render([
            'cartItems' => $cart->items,
        ]);
    }

    public function increaseQuantity(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $quantityType = isset($_POST['quantity_type']) ? ItemQuantityEnum::from($_POST['quantity_type']) : ItemQuantityEnum::GENERAL;

        $this->cartRepository->increaseQuantity($cart->id, $_POST['item_id'], $quantityType);

        Response::redirect('/cart');
    }

    public function decreaseQuantity(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $quantityType = isset($_POST['quantity_type']) ? ItemQuantityEnum::from($_POST['quantity_type']) : ItemQuantityEnum::GENERAL;

        $this->cartRepository->decreaseQuantity($cart->id, $_POST['item_id'], $quantityType);

        Response::redirect('/cart');
    }

    public function addItem(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $eventModel = match ($_POST['event_type']) {
            'dance' => EventDance::class,
            'yummy' => EventYummy::class,
            'history' => EventHistory::class,
            default => null,
        };
        $quantityModels = [];

        if ($eventModel === EventYummy::class) {
            $adultQuantity = $_POST['adult_quantity'];
            $adultModel = new CartItemQuantity();
            $adultModel->type = ItemQuantityEnum::ADULT;
            $adultModel->quantity = $adultQuantity;
            $quantityModels[] = $adultModel;

            $childQuantity = $_POST['child_quantity'];
            $childModel = new CartItemQuantity();
            $childModel->type = ItemQuantityEnum::CHILD;
            $childModel->quantity = $childQuantity;
            $quantityModels[] = $childModel;
        } else if ($eventModel === EventHistory::class) {
            $eventIds = explode(',', $_POST['event_ids']);
            $_POST['event_id'] = array_rand($eventIds);

            $quantityModel = new CartItemQuantity();
            $quantityModel->type = ItemQuantityEnum::from($_POST['ticket_type']);
            $quantityModel->quantity = $_POST['quantity'];
            $quantityModels[] = $quantityModel;
        } else {
            $quantityModel = new CartItemQuantity();
            $quantityModel->type = ItemQuantityEnum::GENERAL;
            $quantityModel->quantity = $_POST['quantity'];
            $quantityModels[] = $quantityModel;
        }

        $newCartItem = new CartItem();
        $newCartItem->cart_id = $cart->id;
        $newCartItem->event_model = $eventModel;
        $newCartItem->event_id = $_POST['event_id'];
        $newCartItem->note = $_POST['note'] ?? '';
        $newCartItem->quantities = $quantityModels;

        $this->cartRepository->addCartItem($newCartItem);

        Response::redirect("/{$_POST['event_type']}");
    }

    public function removeItem(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $this->cartRepository->deleteCartItem($cart->id, $_POST['item_id']);

        Response::redirect('/cart');
    }

    public function checkout()
    {
        $result = $this->orderService->validateAvailability($_POST['order']);
        if (isset($result['error'])) {
            return $this->pageLoader->setPage('cart/index')->render($result);
        }
        if ($_POST['paymentChoice'] == 'payNow') {
            Response::redirect('/checkout');
        } else {
            $this->orderService->createOrder($_POST['order']);
            Response::redirect('/checkout/pay_later');
        }
    }
}
