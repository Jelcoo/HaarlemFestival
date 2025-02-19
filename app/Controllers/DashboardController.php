<?php
namespace App\Controllers;

class DashboardController extends Controller
{
  private array $sidebarItems = [
    'home' => ['label' => 'Home', 'url' => '/dashboard'],
    'users' => ['label' => 'Users', 'url' => '/dashboard/users'],
  ];
  private string $content = "";
  private string $activePage;

  public function __construct()
  {
    parent::__construct();
    $this->activePage = 'dashboard';
  }

  public function index(string $contentView = 'default'): string
  {
    $this->activePage = ($contentView === 'default') ? 'home' : $contentView;

    ob_start();
    include __DIR__ . "/../../resources/views/pages/dashboard/content/{$contentView}.php";
    $this->content = ob_get_clean();

    return $this->pageLoader->setPage('dashboard/index')->render([
      'activePage' => $this->activePage,
      'sidebarItems' => $this->sidebarItems,
      'content' => $this->content
    ]);
  }

  public function users(): string
  {
    return $this->index('users');
  }
}

