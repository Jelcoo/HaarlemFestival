<?php

namespace App\Controllers\Dashboard;

use App\Enum\UserRoleEnum;
use App\Validation\UniqueRule;
use Rakit\Validation\Validator;
use App\Repositories\UserRepository;

class UsersController extends DashboardController
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

    public function deleteUser(): void
    {
        $userId = $_POST['id'] ?? null;

        if (!$userId) $this->redirectToUsers(false,'Invalid user ID.');

        $success = (bool) $this->userRepository->deleteUser($userId);
        $this->redirectToUsers($success, $success ? 'User deleted successfully.' : 'Failed to delete user.');
    }

    public function editUser(): string
    {
        $userId = $_GET['id'] ?? null;

        if (!$userId) $this->redirectToUsers(false, 'Invalid user ID.');

        $user = $this->userRepository->getUserById($userId);

        if (!$user) $this->redirectToUsers(false, 'User not found.');

        $formData = [
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'role' => $user->role->value,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'city' => $user->city,
            'postal_code' => $user->postal_code,
        ];

        return $this->showUserForm('edit', $formData);
    }

    public function editUserPost()
    {
        try {
            $userId = $_POST['id'] ?? null;
            if (!$userId) throw new \Exception('Invalid user ID.');

            $existingUser = $this->userRepository->getUserById($userId);
            if (!$existingUser) throw new \Exception('User not found.');

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
            } else {
                $rules['email'] = 'required|email|max:255';
            }

            if (!isset($_POST['role']) || !in_array($_POST['role'], array_column(UserRoleEnum::cases(), 'value'))) {
                throw new \Exception('Invalid or missing role');
            }

            $validation = $validator->validate($_POST, $rules);

            if ($validation->fails()) {
                return $this->showUserForm('edit', $_POST, $validation->errors()->all());
            }

            $existingUser->firstname = $_POST['firstname'];
            $existingUser->lastname = $_POST['lastname'];
            $existingUser->email = $_POST['email'];
            $existingUser->role = UserRoleEnum::from($_POST['role']);
            $existingUser->phone_number = $_POST['phone_number'] ?? null;
            $existingUser->address = $_POST['address'] ?? null;
            $existingUser->city = $_POST['city'] ?? null;
            $existingUser->postal_code = $_POST['postal_code'] ?? null;

            $this->userRepository->updateUserAdmin($existingUser);

            $this->redirectToUsers(true, 'User updated successfully.');
        } catch (\Exception $e) {
            return $this->showUserForm('edit', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    public function createUser(): string
    {
        return $this->showUserForm();
    }

    public function createUserPost()
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate(
                $_POST,
                [
                    'firstname' => 'required|alpha|max:255',
                    'lastname' => 'required|alpha|max:255',
                    'email' => 'required|email|max:255',
                    'password' => 'required|min:6',
                    'role' => 'required|in:' . implode(',', array_column(UserRoleEnum::cases(), 'value')),
                    'phone_number' => 'nullable|max:255',
                    'address' => 'nullable|max:255',
                    'city' => 'nullable|max:255',
                    'postal_code' => 'nullable|max:20',
                ]
            );

            if ($validation->fails()) {
                return $this->showUserForm('create', $_POST, $validation->errors()->all());
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

            $userData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $this->userRepository->createUser($userData);
            $this->redirectToUsers(true, "User '{$_POST['firstname']} {$_POST['lastname']}' created successfully.");
        } catch (\Exception $e) {
            return $this->showUserForm('create', $_POST, ['Error: ' . $e->getMessage()]);
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

    public function showUserForm(string $mode = 'create', array $formData = [], array $errors = [], array $status = []): string
    {
        return $this->showForm(
            'users',
            $mode,
            $formData,
            $errors,
            $status,
            ['roles' => array_column(UserRoleEnum::cases(), 'value')]
        );
    }


    public function exportUsers(): void
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
