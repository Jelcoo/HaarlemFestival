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

            $formData = $_SESSION['form_data'] ?? [];
            unset($_SESSION['form_data']);

            return $this->renderPage('users_create', [
                'roles' => array_column(UserRoleEnum::cases(), 'value'),
                'formData' => $formData,
                'status' => $this->getStatus(),
            ]);
        }

        return $this->renderPage('users', [
            'users' => $this->userRepository->getSortedUsers($searchQuery, $sortColumn, $sortDirection),
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

        $action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);
        $userId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        match ($action) {
            'delete' => $userId ? $this->deleteUser($userId) : $this->redirectToUsers(false, 'Invalid user ID.'),
            'update' => $userId ? $this->updateUser($userId) : $this->redirectToUsers(false, 'Invalid user ID.'),
            'create' => $this->showCreateUserForm(),
            'createNewUser' => $this->createNewUser(),
            default => $this->redirectToUsers(false, 'Invalid action.'),
        };
    }

    private function validateUserInput(array $input, bool $isUpdate = false): array
    {
        $errors = [];

        if (!$isUpdate || isset($input['firstname'])) {
            if (empty($input['firstname']) || !preg_match("/^[a-zA-Z-' ]*$/", $input['firstname'])) {
                $errors[] = 'Invalid first name.';
            }
        }

        if (!$isUpdate || isset($input['lastname'])) {
            if (empty($input['lastname']) || !preg_match("/^[a-zA-Z-' ]*$/", $input['lastname'])) {
                $errors[] = 'Invalid last name.';
            }
        }

        if (!$isUpdate || isset($input['email'])) {
            if (empty($input['email']) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email address.';
            }
        }

        if (!$isUpdate || isset($input['role'])) {
            $validRoles = array_column(UserRoleEnum::cases(), 'value');

            $roleValue = $input['role'] instanceof UserRoleEnum
                ? $input['role']->value
                : $input['role'];

            if (!in_array($roleValue, $validRoles, true)) {
                $errors[] = 'Invalid role selected.';
            }
        }

        return $errors;
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
            'firstname' => filter_input(INPUT_POST, 'firstname', FILTER_DEFAULT) ?? $existingUser->firstname,
            'lastname' => filter_input(INPUT_POST, 'lastname', FILTER_DEFAULT) ?? $existingUser->lastname,
            'email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? $existingUser->email,
            'role' => UserRoleEnum::from(filter_input(INPUT_POST, 'role', FILTER_DEFAULT)) ?? $existingUser->role,
            'address' => filter_input(INPUT_POST, 'address', FILTER_DEFAULT) ?? $existingUser->address,
            'city' => filter_input(INPUT_POST, 'city', FILTER_DEFAULT) ?? $existingUser->city,
            'postal_code' => filter_input(INPUT_POST, 'postal_code', FILTER_DEFAULT) ?? $existingUser->postal_code,
        ];

        $errors = $this->validateUserInput($fieldsToUpdate, true);
        if (!empty($errors)) {
            $this->redirectToUsers(false, implode(' ', $errors));
            return;
        }

        foreach ($fieldsToUpdate as $field => $value) {
            $existingUser->$field = $value;
        }

        $updatedUser = $this->userRepository->updateUserAdmin($existingUser);
        $this->redirectToUsers(!empty($updatedUser), $updatedUser ? 'User updated successfully.' : 'No changes were made.');
    }

    private function createNewUser(): void
    {
        $user = [
            'firstname' => filter_input(INPUT_POST, 'firstname', FILTER_DEFAULT),
            'lastname' => filter_input(INPUT_POST, 'lastname', FILTER_DEFAULT),
            'email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
            'password' => filter_input(INPUT_POST, 'password', FILTER_DEFAULT),
            'role' => filter_input(INPUT_POST, 'role', FILTER_DEFAULT) ?? UserRoleEnum::USER->value,
            'address' => filter_input(INPUT_POST, 'address', FILTER_DEFAULT) ?? '',
            'city' => filter_input(INPUT_POST, 'city', FILTER_DEFAULT) ?? '',
            'postal_code' => filter_input(INPUT_POST, 'postal_code', FILTER_DEFAULT) ?? '',
        ];

        $errors = $this->validateUserInput($user);
        if (!empty($errors)) {
            $_SESSION['show_create_user_form'] = true;
            $_SESSION['form_data'] = $user;

            $this->redirectToUsers(false, implode(' ', $errors));
            return;
        }

        try {
            $this->userRepository->createUser($user);
        } catch (\Exception $e) {
            $_SESSION['show_create_user_form'] = true;
            $_SESSION['form_data'] = $user;
            $_SESSION['form_errors'] = ['Failed to create user: ' . $e->getMessage()];

            $this->redirectToUsers();
            return;
        }

        $this->redirectToUsers(true, "User '{$user['firstname']} {$user['lastname']}' created successfully.");
    }


    private function getColumns(): array
    {
        $roles = array_column(UserRoleEnum::cases(), 'value');

        return [
            'id' => [
                'label' => 'ID',
                'sortable' => true,
                'editable' => false,
                'editable_type' => null,
                'required' => true,
            ],
            'firstname' => [
                'label' => 'First Name',
                'sortable' => true,
                'editable' => true,
                'editable_type' => 'text',
                'required' => true,
            ],
            'lastname' => [
                'label' => 'Last Name',
                'sortable' => true,
                'editable' => true,
                'editable_type' => 'text',
                'required' => true,
            ],
            'email' => [
                'label' => 'Email',
                'sortable' => true,
                'editable' => true,
                'editable_type' => 'email',
                'required' => true,
            ],
            'role' => [
                'label' => 'Role',
                'sortable' => true,
                'editable' => true,
                'editable_type' => 'select',
                'options' => $roles
            ],
            'address' => [
                'label' => 'Address',
                'sortable' => false,
                'editable' => true,
                'editable_type' => 'text'
            ],
            'city' => [
                'label' => 'City',
                'sortable' => true,
                'editable' => true,
                'editable_type' => 'text'
            ],
            'postal_code' => [
                'label' => 'Postal Code',
                'sortable' => true,
                'editable' => true,
                'editable_type' => 'text'
            ],
            'created_at' => [
                'label' => 'Created At',
                'sortable' => true,
                'editable' => false,
                'editable_type' => null
            ],
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
