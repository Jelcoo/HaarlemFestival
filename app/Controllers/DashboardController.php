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
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $sortColumn = $_GET['sort'] ?? 'id';
    $sortDirection = $_GET['direction'] ?? 'asc';

    $columns = [
      'id' => ['label' => 'ID', 'sortable' => true],
      'firstname' => ['label' => 'First Name', 'sortable' => true],
      'lastname' => ['label' => 'Last Name', 'sortable' => true],
      'email' => ['label' => 'Email', 'sortable' => true],
      'role' => ['label' => 'Role', 'sortable' => true],
      'address' => ['label' => 'Address', 'sortable' => false],
      'city' => ['label' => 'City', 'sortable' => true],
      'postal_code' => ['label' => 'Postal Code', 'sortable' => false],
      'created_at' => ['label' => 'Created At', 'sortable' => true],
      'stripe_customer_id' => ['label' => 'Stripe ID', 'sortable' => false],
      'actions' => ['label' => 'Actions', 'sortable' => false],
    ];


    $users = $this->userRepository->getSortedUsers($sortColumn, $sortDirection);

    $status = $_SESSION['status'] ?? '';
    unset($_SESSION['status']);

    if (!empty($_SESSION['show_create_user_form'])) {
      unset($_SESSION['show_create_user_form']);
      return $this->pageLoader->setPage('dashboard/index')->render([
        'activePage' => 'users_create',
        'sidebarItems' => $this->getSidebarItems(),
        'content' => $this->loadContent('users_create'),
      ]);
    }

    return $this->pageLoader->setPage('dashboard/index')->render([
      'activePage' => 'users',
      'sidebarItems' => $this->getSidebarItems(),
      'content' => $this->loadContent('users', [
        'users' => $users,
        'status' => $status,
        'columns' => $columns,
        'sortColumn' => $sortColumn,
        'sortDirection' => $sortDirection,
      ]),
    ]);
  }

  public function handleAction(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $action = $_POST['action'] ?? null;
      $userId = $_POST['id'] ?? null;

      switch ($action) {
        case 'delete':
          if ($userId)
            $this->deleteUser($userId);
          break;
        case 'update':
          if ($userId)
            $this->updateUser($userId);
          break;
        case 'create':
          $_SESSION['show_create_user_form'] = true;
          Response::redirect('/dashboard/users');
          break;
        case 'createNewUser':
          $this->createNewUser();
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

  private function createNewUser(): void
  {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
      $this->redirectToUsers(0, 'Please fill in all required fields.');
      return;
    }

    $user = [
      'firstname' => $_POST['firstname'],
      'lastname' => $_POST['lastname'],
      'email' => $_POST['email'],
      'password' => '',
      'role' => isset($_POST['role']) ? UserRoleEnum::from(strtolower($_POST['role']))->value : UserRoleEnum::USER->value,
      'address' => $_POST['address'] ?? '',
      'city' => $_POST['city'] ?? '',
      'postal_code' => $_POST['postal_code'] ?? '',
      'stripe_customer_id' => $_POST['stripe_customer_id'] ?? '',
    ];

    $createdUser = $this->userRepository->createUser($user);

    if ($createdUser) {
      $this->redirectToUsers(1, 'User created successfully.');
    } else {
      $this->redirectToUsers(0, 'Failed to create user.');
    }
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
