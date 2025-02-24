<?php

namespace App\Controllers;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        return $this->pageLoader->setPage('dashboard/index')->render([
            'activePage' => 'home',
            'sidebarItems' => $this->getSidebarItems(),
            'content' => $this->loadContent('default'),
        ]);
    }

    protected function renderPage(string $page, array $data): string
    {
        return $this->pageLoader->setPage('dashboard/index')->render([
            'activePage' => $page,
            'sidebarItems' => $this->getSidebarItems(),
            'content' => $this->loadContent($page, $data),
        ]);
    }

    protected function getSidebarItems(): array
    {
        return [
            'home' => ['label' => 'Home', 'url' => '/dashboard'],
            'users' => ['label' => 'Users', 'url' => '/dashboard/users'],
        ];
    }

    protected function loadContent(string $view, array $data = []): string
    {
        extract($data);

        ob_start();
        include __DIR__ . "/../../resources/views/pages/dashboard/content/{$view}.php";

        return ob_get_clean();
    }
}
