<?php

namespace App\Controllers;

use App\Application\Response;
use App\Repositories\OrderRepository;

class CartController extends Controller
{
    private OrderRepository $orderRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepository = new OrderRepository();
    }

    public function index(array $paramaters = [])
    {
        return $this->pageLoader->setPage('cart/index')->render($paramaters);
    }

    public function checkout()
    {
        if ($_POST['paymentChoice'] == 'payNow') {
            Response::redirect('/checkout');
        } else {
            $this->createOrder($_POST['order']);
            Response::redirect('/checkout/pay_later');
        }
    }

    private function createOrder($data)
    {
        $json = json_decode($data, true);
        $available = $this->orderRepository->checkAvailability($json);
        if (count($available) > 0) {
            $onlyHistory = true;

            foreach ($available as $item) {
                if (!array_key_exists('history', $item)) {
                    $onlyHistory = false;
                    break;
                }
            }

            if ($onlyHistory) {
                // Continue processing, no return here
                foreach ($available as $item) {
                    if ($item['history'] != null) {
                        foreach ($json['history'] as &$historyItem) {
                            if (isset($historyItem['event_id']) && is_array($historyItem['event_id'])) {
                                // Remove matching event IDs
                                $historyItem['event_id'] = array_values(array_filter($historyItem['event_id'], function ($id) use ($item) {
                                    return $id != $item['history'];
                                }));

                                if (count($historyItem['event_id']) === 0) {
                                    throw new \Exception('Event ID list is empty after removing already booked items.');
                                }
                            }
                        }
                        unset($historyItem);
                    }
                }
            } else {
                echo json_encode($available);

                return;
            }
        }
        $this->orderRepository->createOrder($json);
    }
}
