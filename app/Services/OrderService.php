<?php

namespace App\Services;

use App\Repositories\OrderRepository;

class OrderService
{
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function validateAvailability($data)
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
                                    $errors = [];

                                    foreach ($available as $item) {
                                        if (isset($item['dance'])) {
                                            $eventId = $item['dance'];
                                            foreach ($json['dance'] as $event) {
                                                if ($event['event_id'] == $eventId) {
                                                    $errors[] = "{$event['name']} has {$item['reason']}. So please remove it from your cart";
                                                    break;
                                                }
                                            }
                                        } elseif (isset($item['yummy'])) {
                                            $eventId = $item['yummy'];
                                            foreach ($json['yummy'] as $event) {
                                                if ($event['event_id'] == $eventId) {
                                                    $errors[] = "{$event['name']} has {$item['reason']}. So please remove it from your cart";
                                                    break;
                                                }
                                            }
                                        } elseif (isset($item['history'])) {
                                            $eventId = $item['history'];
                                            foreach ($json['history'] as $event) {
                                                if (in_array($eventId, $event['event_id'])) {
                                                    $errors[] = "{$event['name']} has {$item['reason']}. So please remove it from your cart";
                                                    break;
                                                }
                                            }
                                        }
                                    }

                                    return [
                                        'error' => [$errors],
                                    ];
                                }
                            }
                        }
                        unset($historyItem);
                    }
                }
            } else {
                $errors = [];

                foreach ($available as $item) {
                    if (isset($item['dance'])) {
                        $eventId = $item['dance'];
                        foreach ($json['dance'] as $event) {
                            if ($event['event_id'] == $eventId) {
                                $errors[] = "{$event['name']} has {$item['reason']}. So please remove it from your cart";
                                break;
                            }
                        }
                    } elseif (isset($item['yummy'])) {
                        $eventId = $item['yummy'];
                        foreach ($json['yummy'] as $event) {
                            if ($event['event_id'] == $eventId) {
                                $errors[] = "{$event['name']} has {$item['reason']}. So please remove it from your cart";
                                break;
                            }
                        }
                    } elseif (isset($item['history'])) {
                        $eventId = $item['history'];
                        foreach ($json['history'] as $event) {
                            if (in_array($eventId, $event['event_id'])) {
                                $errors[] = "{$event['name']} has {$item['reason']}. So please remove it from your cart";
                                break;
                            }
                        }
                    }
                }

                return [
                    'error' => [$errors],
                ];
            }
        }
    }

    public function createOrder($data)
    {
        $json = json_decode($data, true);

        return $this->orderRepository->createOrder($json);
    }
}
