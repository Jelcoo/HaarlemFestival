<?php

namespace App\Controllers;

use Rakit\Validation\Validator;
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

    private function deleteUser(int $userId): void
    {
        $success = $this->userRepository->deleteUser($userId);
        $this->redirectToUsers(!empty($success), $success ? 'User deleted successfully.' : 'Failed to delete user.');
    }

    private function updateUser(int $userId): void
    {
        try {
            $existingUser = $this->userRepository->getUserById($userId);

            if (!$existingUser) {
                throw new \Exception('User not found.');
            }

            $fieldsToUpdate = array_intersect_key($_POST, array_flip([
                'firstname',
                'lastname',
                'email',
                'role',
                'address',
                'city',
                'postal_code'
            ]));

            if (isset($fieldsToUpdate['role'])) {
                $fieldsToUpdate['role'] = UserRoleEnum::from(strtolower($fieldsToUpdate['role']));
            }

            foreach ($fieldsToUpdate as $field => $value) {
                $existingUser->$field = $value;
            }

            $updatedUser = $this->userRepository->updateUserAdmin($existingUser);
            $this->redirectToUsers(true, "User '{$_POST['firstname']} {$_POST['lastname']}' updated successfully.");
        } catch (\Exception $e) {
            $_SESSION['show_edit_user_form'] = true;
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToUsers(false, $e->getMessage());
        }
    }

    private function createNewUser(): void
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate($_POST, [
                'firstname' => 'required|alpha|max:255',
                'lastname' => 'required|alpha|max:255',
                'email' => 'required|email|max:255',
                'role' => 'required|in:' . implode(',', array_column(UserRoleEnum::cases(), 'value')),
                'address' => 'nullable|max:255',
                'city' => 'nullable|max:255',
                'postal_code' => 'nullable|max:20',
            ]);

            if ($validation->fails()) {
                $_SESSION['show_create_user_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $userData = array_intersect_key($_POST, array_flip([
                'firstname',
                'lastname',
                'email',
                'password',
                'role',
                'address',
                'city',
                'postal_code'
            ]));

            $this->userRepository->createUser($userData);
            $this->redirectToUsers(true, "User '{$_POST['firstname']} {$_POST['lastname']}' created successfully.");
        } catch (\Exception $e) {
            $_SESSION['show_create_user_form'] = true;
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToUsers(false, $e->getMessage());
        }
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
        $this->redirectTo('users', $success, $message);
    }

    private function showCreateUserForm(): void
    {
        $_SESSION['show_create_user_form'] = true;
        $this->redirectToUsers(false, '');
    }
}
