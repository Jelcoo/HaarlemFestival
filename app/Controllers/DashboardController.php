<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Application\Response;

class DashboardController extends Controller
{
  private UserRepository $userRepository;

  public function __construct()
  {
    parent::__construct();
    $this->userRepository = new UserRepository();
  }

  public function index(): string
  {
    return $this->pageLoader->setPage('dashboard/index')->render([
      'activePage' => 'home',
      'sidebarItems' => $this->getSidebarItems(),
      'content' => $this->loadContent('default'),
    ]);
  }

  public function users(): string
  {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $users = $this->userRepository->getAllUsers();

    return $this->pageLoader->setPage('dashboard/index')->render([
      'activePage' => 'users',
      'sidebarItems' => $this->getSidebarItems(),
      'content' => $this->loadContent('users', ['users' => $users]),
    ]);
  }

  public function handleAction(): void
  {
    $action = $_POST['action'] ?? null;
    $userId = $_POST['id'] ?? null;

    if (!$userId || !$action) {
      $this->redirectToUsers();
      return;
    }

    switch ($action) {
      case 'delete':
        $this->deleteUser($userId);
        break;
      case 'edit':
        $this->editUser($userId);
        break;
      default:
        $this->redirectToUsers();
        break;
    }
  }

  private function deleteUser(int $userId): void
  {
    $deletedUser = $this->userRepository->deleteUser($userId);

    if ($deletedUser) {
      $this->redirectToUsers('success');
    } else {
      $this->redirectToUsers('error');
    }
  }

  private function editUser(int $userId): void
  {
    Response::redirect('/dashboard/user/edit?id=' . $userId);
  }

  private function redirectToUsers(string $status = ''): void
  {
    $redirectUrl = '/dashboard/users';
    if ($status) {
      $redirectUrl .= '?status=' . $status;
    }
    Response::redirect($redirectUrl);
  }

  private function getSidebarItems(): array
  {
    return [
      'home' => ['label' => 'Home', 'url' => '/dashboard'],
      'users' => ['label' => 'Users', 'url' => '/dashboard/users'],
    ];
  }

  private function loadContent(string $view, array $data = []): string
  {
    extract($data);

    ob_start();
    include __DIR__ . "/../../resources/views/pages/dashboard/content/{$view}.php";
    return ob_get_clean();
  }
}

