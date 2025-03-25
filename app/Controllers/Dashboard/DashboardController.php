<?php

namespace App\Controllers\Dashboard;

use Carbon\Carbon;
use App\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        return $this->renderPage('home', []);
    }

    protected function renderPage(string $page, array $data): string
    {
        return $this->pageLoader
            ->setLayout('dashboard')
            ->setPage('dashboard/index')
            ->render(
                [
                    'activePage' => $page,
                    'sidebarItems' => $this->getSidebarItems(),
                    'content' => $this->loadContent($page, $data),
                ]
            );
    }

    protected function getSidebarItems(): array
    {
        return [
            'home' => ['label' => 'Home', 'url' => '/dashboard'],
            'general' => [
                'label' => 'General',
                'users' => ['label' => 'Users', 'url' => '/dashboard/users'],
                'restaurants' => ['label' => 'Restaurants', 'url' => '/dashboard/restaurants'],
                'locations' => ['label' => 'Locations', 'url' => '/dashboard/locations'],
                'artists' => ['label' => 'Artists', 'url' => '/dashboard/artists'],
            ],
            'events' => [
                'label' => 'Events',
                'dance_events' => ['label' => 'Dance', 'url' => '/dashboard/events/dance'],
                'yummy_events' => ['label' => 'Yummy', 'url' => '/dashboard/events/yummy'],
                'history_events' => ['label' => 'History', 'url' => '/dashboard/events/history'],
            ],
            'orders' => [
                'label' => 'Orders',
                'orders' => ['label' => 'Orders', 'url' => '/dashboard/orders'],
            ],
        ];
    }

    protected function loadContent(string $view, array $data = []): string
    {
        extract($data);

        ob_start();
        include __DIR__ . "/../../../resources/views/pages/dashboard/content/{$view}.php";

        return ob_get_clean();
    }

    protected function redirectTo(string $redirect, bool $success = false, string $message = ''): void
    {
        $_SESSION['status'] = ['status' => $success, 'message' => $message];
        header('Location: /dashboard/' . $redirect);
        exit;
    }

    protected function getStatus(): array
    {
        $status = $_SESSION['status'] ?? ['status' => false, 'message' => ''];
        unset($_SESSION['status']);

        return $status;
    }

    protected function showForm(string $formName, string $mode = 'create', array $formData = [], array $errors = [], array $status = [], array $customData = []): string
    {
        return $this->renderPage(
            "/../../../components/dashboard/forms/{$formName}_form",
            array_merge(
                [
                    'mode' => $mode,
                    'formData' => $formData,
                    'errors' => $errors,
                    'status' => $status + ['status' => empty($errors)],
                ],
                $customData
            )
        );
    }

    // https://phppot.com/php/php-array-to-csv/
    protected function exportToCsv(string $filename, array $data, array $columns): void
    {
        if (ob_get_level()) {
            ob_end_clean();
        }

        $timestamp = Carbon::now()->format('d-m-Y_H-i-s');
        $filename = $filename . "_$timestamp.csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        fputcsv($output, array_values($columns));

        foreach ($data as $row) {
            $csvRow = [];

            foreach (array_keys($columns) as $key) {
                if (str_contains($key, '.')) {
                    $parts = explode('.', $key);
                    $value = $row;
                    foreach ($parts as $part) {
                        $value = $value->$part ?? '';
                        if (empty($value)) {
                            break;
                        }
                    }
                } else {
                    $value = $row->$key ?? '';
                }

                if (is_object($value) && enum_exists(get_class($value))) {
                    $value = $value->value;
                }

                $csvRow[] = $value;
            }
            fputcsv($output, $csvRow);
        }

        fclose($output);
        exit;
    }
}
