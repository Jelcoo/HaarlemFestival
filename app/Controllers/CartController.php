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
use App\Enum\InvoiceStatusEnum;
use App\Models\CartItemQuantity;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;

class CartController extends Controller
{
    private OrderService $orderService;
    private CartService $cartService;
    private CartRepository $cartRepository;
    private OrderRepository $orderRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->cartRepository = new CartRepository();
        $this->orderRepository = new OrderRepository();
    }

    public function index(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart(true, true);
        $_SESSION['cart'] = true;
        return $this->pageLoader->setPage('cart/index')->render([
            'cartItems' => $cart->items,
        ] + $paramaters);
    }

    public function increaseQuantity(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $quantityType = isset($_POST['quantity_type']) ? ItemQuantityEnum::from($_POST['quantity_type']) : ItemQuantityEnum::GENERAL;

        $this->cartRepository->increaseQuantity($cart->id, $_POST['item_id'], $quantityType);

        Response::redirect('/cart?message=Added 1 item to cart');
    }

    public function decreaseQuantity(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $quantityType = isset($_POST['quantity_type']) ? ItemQuantityEnum::from($_POST['quantity_type']) : ItemQuantityEnum::GENERAL;

        $this->cartRepository->decreaseQuantity($cart->id, $_POST['item_id'], $quantityType);

        Response::redirect('/cart?message=Removed 1 item from cart');
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
        } elseif ($eventModel === EventHistory::class) {
            $eventIds = explode(',', $_POST['event_ids']);
            foreach ($eventIds as $eventId) {
                $available = $this->orderRepository->checkHistoryTicketAvailable((int) $eventId, $_POST['quantity']);
                if (!$available) {
                    array_splice($eventIds, array_search($eventId, $eventIds), 1);
                }
            }
            if (empty($eventIds)) {
                return $this->index([
                    'error' => 'No more tickets available',
                ]);
            }

            $_POST['event_id'] = $eventIds[0];

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

        Response::redirect("/{$_POST['event_type']}?message=Item added to cart");
    }

    public function removeItem(array $paramaters = [])
    {
        $cart = $this->cartService->getSessionCart();

        $this->cartRepository->deleteCartItem($cart->id, $_POST['item_id']);

        Response::redirect('/cart?message=Item removed from cart');
    }

    public function checkout()
    {
        $cart = $this->cartService->getSessionCart(true);

        $errors = $this->orderService->validateAvailability($cart);
        $errorCount = count(array_filter($errors, function ($errorArray) {
            return !empty($errorArray);
        }));

        if ($errorCount > 0) {
            return $this->index([
                'stockErrors' => $errors,
                'error' => 'There are some items out of stock. Please remove them from your cart.',
            ]);
        }

        if ($_POST['paymentChoice'] == 'payNow') {
            Response::redirect('/checkout');
        } else {
            $invoiceId = $this->orderService->createOrder($cart);
            $this->orderRepository->updateOrderStatus($invoiceId, InvoiceStatusEnum::LATER);
            $this->cartRepository->deleteCart($cart->id);
            Response::redirect('/checkout/pay_later');
        }
    }
}
