<?php

namespace App\Controllers;

use App\Enum\UserRoleEnum;
use App\Repositories\UserRepository;

class DashboardUsersController extends DashboardController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function index(): string
    {
        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToUsers();
        }

        if (!empty($_SESSION['show_create_user_form'])) {
            unset($_SESSION['show_create_user_form']);

            return $this->renderPage('users_create', [
                'roles' => array_column(UserRoleEnum::cases(), 'value'),
            ]);
        }

        return $this->renderPage('users', [
            'users' => $this->userRepository->getSortedUsers($searchQuery, $sortColumn, $sortDirection),
            'roles' => array_column(UserRoleEnum::cases(), 'value'),
            'status' => $this->getStatus(),
            'columns' => $this->getColumns(),
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'searchQuery' => $searchQuery,
        ]);
    }

    public function handleAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $action = $_POST['action'] ?? null;
        $userId = $_POST['id'] ?? null;

        match ($action) {
            'delete' => $userId ? $this->deleteUser($userId) : $this->redirectToUsers(false, 'Invalid user ID.'),
            'update' => $userId ? $this->updateUser($userId) : $this->redirectToUsers(false, 'Invalid user ID.'),
            'create' => $this->showCreateUserForm(),
            'createNewUser' => $this->createNewUser(),
            default => $this->redirectToUsers(false, 'Invalid action.'),
        };
    }

    private function deleteUser(int $userId): void
    {
        $success = $this->userRepository->deleteUser($userId);
        $this->redirectToUsers(!empty($success), $success ? 'User deleted successfully.' : 'Failed to delete user.');
    }

    private function updateUser(int $userId): void
    {
        $existingUser = $this->userRepository->getUserById($userId);

        if (!$existingUser) {
            $this->redirectToUsers(false, 'User not found.');

            return;
        }

        $fieldsToUpdate = [
            'firstname' => $_POST['firstname'] ?? $existingUser->firstname,
            'lastname' => $_POST['lastname'] ?? $existingUser->lastname,
            'email' => $_POST['email'] ?? $existingUser->email,
            'role' => isset($_POST['role']) ? UserRoleEnum::from(strtolower($_POST['role'])) : $existingUser->role,
            'address' => $_POST['address'] ?? $existingUser->address,
            'city' => $_POST['city'] ?? $existingUser->city,
            'postal_code' => $_POST['postal_code'] ?? $existingUser->postal_code,
        ];

        foreach ($fieldsToUpdate as $field => $value) {
            $existingUser->$field = $value;
        }

        $updatedUser = $this->userRepository->updateUserAdmin($existingUser);
        $this->redirectToUsers(!empty($updatedUser), $updatedUser ? 'User updated successfully.' : 'No changes were made.');
    }

    private function createNewUser(): void
    {
        if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
            $this->redirectToUsers(false, 'Please fill in all required fields.');

            return;
        }

        $user = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'password' => '', // TODO
            'role' => $_POST['role'] ?? UserRoleEnum::USER->value,
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'postal_code' => $_POST['postal_code'] ?? '',
            'stripe_customer_id' => $_POST['stripe_customer_id'] ?? '',
        ];

        try {
            $this->userRepository->createUser($user);
        } catch (\Exception $e) {
            $this->redirectToUsers(false, 'Failed to create user: ' . $e->getMessage());

            return;
        }

        $this->redirectToUsers(true, 'User created successfully.');
    }

    private function getColumns(): array
    {
        return [
            'id' => ['label' => 'ID', 'sortable' => true],
            'firstname' => ['label' => 'First Name', 'sortable' => true],
            'lastname' => ['label' => 'Last Name', 'sortable' => true],
            'email' => ['label' => 'Email', 'sortable' => true],
            'role' => ['label' => 'Role', 'sortable' => true],
            'address' => ['label' => 'Address', 'sortable' => false],
            'city' => ['label' => 'City', 'sortable' => true],
            'postal_code' => ['label' => 'Postal Code', 'sortable' => true],
            'created_at' => ['label' => 'Created At', 'sortable' => true],
            'stripe_customer_id' => ['label' => 'Stripe ID', 'sortable' => false],
            'actions' => ['label' => 'Actions', 'sortable' => false],
        ];
    }

    private function getStatus(): array
    {
        $status = $_SESSION['status'] ?? ['status' => false, 'message' => ''];
        unset($_SESSION['status']);

        return $status;
    }

    private function redirectToUsers(bool $success = false, string $message = ''): void
    {
        $_SESSION['status'] = ['status' => $success, 'message' => $message];
        header('Location: /dashboard/users');
        exit;
    }

    private function showCreateUserForm(): void
    {
        $_SESSION['show_create_user_form'] = true;
        $this->redirectToUsers(false, '');
    }
}
