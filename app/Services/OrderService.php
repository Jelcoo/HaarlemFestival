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
        $availabilityList = $this->orderRepository->checkAvailability($json);

        if (empty($availabilityList)) {
            return; // No issues, continue processing
        }

        $onlyHistory = array_reduce($availabilityList, function ($carry, $item) {
            return $carry && array_key_exists('history', $item);
        }, true);

        if ($onlyHistory) {
            return $this->processHistoryItems($availabilityList, $json);
        }

        return $this->generateErrorMessages($availabilityList, $json);
    }

    private function processHistoryItems(array &$availabilityList, array &$json)
    {
        foreach ($availabilityList as $item) {
            if (!isset($item['history']) || $item['history'] === null) {
                continue;
            }

            foreach ($json['history'] as &$historyItem) {
                if (!isset($historyItem['event_id']) || !is_array($historyItem['event_id'])) {
                    continue;
                }

                // Remove matching event IDs
                $historyItem['event_id'] = array_values(array_filter(
                    $historyItem['event_id'],
                    fn($id) => $id != $item['history']
                ));

                if (empty($historyItem['event_id'])) {
                    return $this->generateErrorMessages($availabilityList, $json);
                }
            }
            unset($historyItem);
        }
    }

    private function generateErrorMessages(array $availabilityList, array $json)
    {
        $errors = [];

        foreach ($availabilityList as $item) {
            $eventId = $item['dance'] ?? $item['yummy'] ?? $item['history'] ?? null;
            $eventType = isset($item['dance']) ? 'dance' : (isset($item['yummy']) ? 'yummy' : 'history');
            
            if (!$eventId || !isset($json[$eventType])) {
                continue;
            }

            foreach ($json[$eventType] as $event) {
                if (
                    ($eventType === 'history' && in_array($eventId, $event['event_id'])) ||
                    ($eventType !== 'history' && $event['event_id'] == $eventId)
                ) {
                    $errors[] = "{$event['name']} has {$item['reason']}. So please remove it from your cart";
                    break;
                }
            }
        }

        return empty($errors) ? null : ['error' => [$errors]];
    }

    public function createOrder($data)
    {
        $json = json_decode($data, true);

        return $this->orderRepository->createOrder($json);
    }
}
