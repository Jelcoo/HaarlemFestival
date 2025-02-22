<?php

namespace App\Controllers;

use App\Enum\UserRoleEnum;
use App\Repositories\UserRepository;
use App\Application\Response;
use App\Models\User;

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
    $users = $this->userRepository->getAllUsers();

    $status = $_SESSION['status'] ?? '';
    unset($_SESSION['status']);

    return $this->pageLoader->setPage('dashboard/index')->render([
      'activePage' => 'users',
      'sidebarItems' => $this->getSidebarItems(),
      'content' => $this->loadContent('users', ['users' => $users, 'status' => $status]),
    ]);
  }

  public function handleAction(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $action = $_POST['action'] ?? null;
      $userId = $_POST['id'] ?? null;

      if (!$userId) {
        $this->redirectToUsers();
        return;
      }

      switch ($action) {
        case 'delete':
          $this->deleteUser($userId);
          break;
        case 'update':
          $this->updateUser($userId);
          break;
        default:
          $this->redirectToUsers();
          break;
      }
    }
  }

  private function deleteUser(int $userId): void
  {
    $deletedUser = $this->userRepository->deleteUser($userId);

    if ($deletedUser) {
      $this->redirectToUsers(1, 'User deleted successfully.');
    } else {
      $this->redirectToUsers(0, 'Failed to delete user.');
    }
  }

  private function updateuser(int $userId): void
  {
    $existingUser = $this->userRepository->getUserById($userId);

    if (!$existingUser) {
      $this->redirectToUsers(0, 'User not found.');
      return;
    }

    $fieldsToUpdate = [
      'firstname' => $_POST['firstname'] ?? $existingUser->firstname,
      'lastname' => $_POST['lastname'] ?? $existingUser->lastname,
      'email' => $_POST['email'] ?? $existingUser->email,
      'role' => isset($_POST['role']) ? UserRoleEnum::from(strtolower($_POST['role'])) : $existingUser->role,
      'address' => $_POST['address'] ?? $existingUser->address,
      'city' => $_POST['city'] ?? $existingUser->city,
      'postal_code' => $_POST['postal_code'] ?? $existingUser->postal_code
    ];

    foreach ($fieldsToUpdate as $field => $value) {
      $existingUser->$field = $value;
    }

    $updatedUser = $this->userRepository->updateUser($existingUser);

    $this->redirectToUsers(
      $updatedUser ? true : false,
      $updatedUser ? 'User updated successfully.' : 'No changes were made.'
    );
  }

  private function redirectToUsers(string $status = '', string $message = ''): void
  {
    if ($status) {
      $_SESSION['status'] = [
        'status' => $status,
        'message' => $message,
      ];
    }
    Response::redirect('/dashboard/users');
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
