<?php
namespace App\Controllers;

use App\Repositories\UserRepository;

class DashboardController extends Controller
{
  private UserRepository $UserRepository;

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

    $this->UserRepository = new UserRepository();
  }

  public function index(string $contentView = 'default', array $data = []): string
  {
    $this->activePage = ($contentView === 'default') ? 'home' : $contentView;

    ob_start();
    include __DIR__ . "/../../resources/views/pages/dashboard/content/{$contentView}.php";
    $this->content = ob_get_clean();

    return $this->pageLoader->setPage('dashboard/index')->render(array_merge([
      'activePage' => $this->activePage,
      'sidebarItems' => $this->sidebarItems,
      'content' => $this->content
    ], $data));
  }

  public function users(): string
  {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $users = $this->UserRepository->getAllUsers();

    return $this->index('users', ['users' => $users]);
  }
}

