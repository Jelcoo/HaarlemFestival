<?php

namespace App\Controllers;

use App\Enum\UserRoleEnum;
use App\Validation\UniqueRule;
use Rakit\Validation\Validator;
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

        if (!empty($_SESSION['show_user_form'])) {
            unset($_SESSION['show_user_form']);

            $formData = $_SESSION['form_data'] ?? [];
            unset($_SESSION['form_data']);

            return $this->renderPage(
                '/../../../components/dashboard/forms/users_form',
                [
                    'roles' => array_column(UserRoleEnum::cases(), 'value'),
                    'formData' => $formData,
                    'status' => $this->getStatus(),
                ]
            );
        }

        return $this->renderPage(
            'users',
            [
                'users' => $this->userRepository->getSortedUsers($searchQuery, $sortColumn, $sortDirection),
                'status' => $this->getStatus(),
                'columns' => $this->getColumns(),
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
                'searchQuery' => $searchQuery,
            ]
        );
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
            'edit' => $userId ? $this->editUser() : $this->redirectToUsers(false, 'Invalid user ID.'),
            'create' => $this->showForm(),
            'createUser' => $this->createNewUser(),
            'export' => $this->exportUsers(),
            default => $this->redirectToUsers(false, 'Invalid action.'),
        };
    }

    private function deleteUser(int $userId): void
    {
        $success = $this->userRepository->deleteUser($userId);
        $this->redirectToUsers(!empty($success), $success ? 'User deleted successfully.' : 'Failed to delete user.');
    }

    private function editUser(): void
    {
        try {
            $userId = $_POST['id'] ?? null;
            if (!$userId) {
                throw new \Exception('Invalid user ID.');
            }

            $existingUser = $this->userRepository->getUserById($userId);
            if (!$existingUser) {
                throw new \Exception('User not found.');
            }

            $_SESSION['show_user_form'] = true;
            $_SESSION['form_data'] = [
                'id' => $existingUser->id,
                'firstname' => $existingUser->firstname,
                'lastname' => $existingUser->lastname,
                'email' => $existingUser->email,
                'password' => $existingUser->password,
                'role' => $existingUser->role->value,
                'phone_number' => $existingUser->phone_number,
                'address' => $existingUser->address,
                'city' => $existingUser->city,
                'postal_code' => $existingUser->postal_code,
            ];

            $this->redirectToUsers();
        } catch (\Exception $e) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToUsers(false, $e->getMessage());
        }
    }

    private function updateUser(int $userId): void
    {
        try {
            $existingUser = $this->userRepository->getUserById($userId);

            if (!$existingUser) {
                throw new \Exception('User not found.');
            }

            $validator = new Validator();
            $validator->addValidator('unique', new UniqueRule());

            $rules = [
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'phone_number' => 'max:255',
                'address' => 'max:255',
                'city' => 'max:255',
                'postal_code' => 'max:255',
            ];
            if ($existingUser->email !== $_POST['email']) {
                $rules['email'] = 'required|email|unique:users,email|max:255';
            }

            $validation = $validator->validate($_POST, $rules);

            if ($validation->fails()) {
                $_SESSION['show_user_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            if (!isset($_POST['role']) || !in_array($_POST['role'], array_column(UserRoleEnum::cases(), 'value'))) {
                throw new \Exception('Invalid or missing role');
            }

            $existingUser->id = $_POST['id'];
            $existingUser->firstname = $_POST['firstname'];
            $existingUser->lastname = $_POST['lastname'];
            $existingUser->email = $_POST['email'];
            $existingUser->role = UserRoleEnum::from(strtolower($_POST['role']));
            $existingUser->phone_number = $_POST['phone_number'] ?? null;
            $existingUser->address = $_POST['address'] ?? null;
            $existingUser->city = $_POST['city'] ?? null;
            $existingUser->postal_code = $_POST['postal_code'] ?? null;

            $this->userRepository->updateUser($existingUser);
            $this->redirectToUsers(true, 'User updated successfully.');
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
            $validation = $validator->validate(
                $_POST,
                [
                    'firstname' => 'required|alpha|max:255',
                    'lastname' => 'required|alpha|max:255',
                    'email' => 'required|email|max:255',
                    'role' => 'required|in:' . implode(',', array_column(UserRoleEnum::cases(), 'value')),
                    'phone_number' => 'nullable|max:255',
                    'address' => 'nullable|max:255',
                    'city' => 'nullable|max:255',
                    'postal_code' => 'nullable|max:20',
                ]
            );

            if ($validation->fails()) {
                $_SESSION['show_create_user_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $userData = array_intersect_key(
                $_POST,
                array_flip(
                    [
                        'firstname',
                        'lastname',
                        'email',
                        'password',
                        'role',
                        'phone_number',
                        'address',
                        'city',
                        'postal_code',
                    ]
                )
            );

            $userData['password'] = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

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
        return [
            'id' => ['label' => 'ID', 'sortable' => true],
            'firstname' => ['label' => 'First Name', 'sortable' => true],
            'lastname' => ['label' => 'Last Name', 'sortable' => true],
            'email' => ['label' => 'Email', 'sortable' => true],
            'role' => ['label' => 'Role', 'sortable' => true],
            'phone_number' => ['label' => 'Phone Number', 'sortable' => true],
            'address' => ['label' => 'Address', 'sortable' => true],
            'city' => ['label' => 'City', 'sortable' => true],
            'postal_code' => ['label' => 'Postal Code', 'sortable' => true],
            'created_at' => ['label' => 'Created At', 'sortable' => true],
        ];
    }

    private function redirectToUsers(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('users', $success, $message);
    }

    private function showForm(): void
    {
        $_SESSION['show_user_form'] = true;
        $this->redirectToUsers();
    }

    private function exportUsers(): void
    {
        $users = $this->userRepository->getAllUsers();

        $columns = [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'role' => 'Role',
            'phone_number' => 'Phone Number',
            'address' => 'Address',
            'city' => 'City',
            'postal_code' => 'Postal Code',
            'created_at' => 'Created At',
        ];

        $this->exportToCsv('users', $users, $columns);
    }
}
