<?php

namespace App\Controllers;

use Carbon\Carbon;

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
            'users' => ['label' => 'Users', 'url' => '/dashboard/users'],
            'orders' => ['label' => 'Orders', 'url' => '/dashboard/orders']
            'restaurants' => ['label' => 'Restaurants', 'url' => '/dashboard/restaurants'],
            'locations' => ['label' => 'Locations', 'url' => '/dashboard/locations'],
            'artists' => ['label' => 'Artists', 'url' => '/dashboard/artists'],
        ];
    }

    protected function loadContent(string $view, array $data = []): string
    {
        extract($data);

        ob_start();
        include __DIR__ . "/../../resources/views/pages/dashboard/content/{$view}.php";

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

    // https://phppot.com/php/php-array-to-csv/
    protected function exportToCsv(string $filename, array $data, array $columns): void
    {
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
    }
}
