<?php

namespace App\Controllers;

use Rakit\Validation\Validator;
use App\Repositories\ArtistRepository;

class DashboardArtistsController extends DashboardController
{
    private ArtistRepository $artistRepository;

    public function __construct()
    {
        parent::__construct();
        $this->artistRepository = new ArtistRepository();
    }

    public function index(): string
    {
        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToArtists();
        }

        if (!empty($_SESSION['show_artist_form'])) {
            unset($_SESSION['show_artist_form']);

            $formData = $_SESSION['form_data'] ?? [];
            unset($_SESSION['form_data']);

            return $this->renderPage('artist_form', [
                'formData' => $formData,
                'status' => $this->getStatus(),
            ]);
        }

        return $this->renderPage('artists', [
            'artists' => $this->artistRepository->getSortedArtists($searchQuery, $sortColumn, $sortDirection),
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
        $artistId = $_POST['id'] ?? null;

        match ($action) {
            'delete' => $artistId ? $this->deleteArtist($artistId) : $this->redirectToArtists(false, 'Invalid artist ID.'),
            'update' => $artistId ? $this->updateArtist($artistId) : $this->redirectToArtists(false, 'Invalid artist ID.'),
            'create' => $this->showForm(),
            'edit' => $artistId ? $this->editArtist() : $this->redirectToArtists(false, 'Invalid artist ID.'),
            'createArtist' => $this->createNewArtist(),
            default => $this->redirectToArtists(false, 'Invalid action.'),
        };
    }

    private function deleteArtist(int $artistId): void
    {
        $success = $this->artistRepository->deleteArtist($artistId);
        $this->redirectToArtists(!empty($success), $success ? 'Artist deleted successfully.' : 'Failed to delete artist');
    }

    private function editArtist(): void
    {
        try {
            $artistId = $_POST['id'] ?? null;
            if (!$artistId) {
                throw new \Exception('Invalid artist ID.');
            }

            $existingArtist = $this->artistRepository->getArtistById($artistId);
            if (!$existingArtist) {
                throw new \Exception('Artist not found.');
            }

            $_SESSION['show_artist_form'] = true;
            $_SESSION['form_data'] = [
                'id' => $existingArtist->id,
                'name' => $existingArtist->name,
                'preview_description' => $existingArtist->preview_description,
                'main_description' => $existingArtist->main_description,
                'iconic_albums' => $existingArtist->iconic_albums,
            ];

            $this->redirectToArtists();
        } catch (\Exception $e) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToArtists(false, $e->getMessage());
        }
    }

    private function updateArtist(int $artistId): void
    {
        try {
            $existingArtist = $this->artistRepository->getArtistById($artistId);

            if (!$existingArtist) {
                $this->redirectToArtists(false, 'Artist not found');
                return;
            }

            $validator = new Validator();
            $validation = $validator->validate($_POST, [
                'name' => 'required|max:255',
                'preview_description' => 'nullable|max:500',
                'main_description' => 'nullable|max:2000',
                'iconic_albums' => 'nullable|max:1000',
            ]);

            if ($validation->fails()) {
                $_SESSION['show_artist_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $fieldsToUpdate = [
                'name' => $_POST['name'] ?? $existingArtist->name,
                'preview_description' => $_POST['preview_description'] ?? $existingArtist->preview_description,
                'main_description' => $_POST['main_description'] ?? $existingArtist->main_description,
                'iconic_albums' => $_POST['iconic_albums'] ?? $existingArtist->iconic_albums,
            ];

            foreach ($fieldsToUpdate as $field => $value) {
                $existingArtist->$field = $value;
            }

            $updatedArtist = $this->artistRepository->updateArtist($existingArtist);
            $this->redirectToArtists(!empty($updatedArtist), $updatedArtist ? 'Artist updated successfully.' : 'No changes were made.');
        } catch (\Exception $e) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToArtists(false, $e->getMessage());
        }
    }

    private function createNewArtist(): void
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate($_POST, [
                'name' => 'required|max:255',
                'preview_description' => 'nullable|max:500',
                'main_description' => 'nullable|max:2000',
                'iconic_albums' => 'nullable|max:1000',
            ]);

            if ($validation->fails()) {
                $_SESSION['show_artist_form'] = true;
                $_SESSION['form_data'] = $_POST;
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $artistData = array_intersect_key($_POST, array_flip([
                'name',
                'preview_description',
                'main_description',
                'iconic_albums'
            ]));

            $createdArtist = $this->artistRepository->createArtist($artistData);
            $this->redirectToArtists(!empty($createdArtist), "Artist '{$artistData['name']}' created successfully.");
        } catch (\Exception $e) {
            $_SESSION['show_artist_form'] = true;
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_errors'] = ['Error: ' . $e->getMessage()];
            $this->redirectToArtists(false, $e->getMessage());
        }
    }


    private function getColumns(): array
    {
        return [
            'id' => ['label' => 'ID', 'sortable' => true],
            'name' => ['label' => 'Artist Name', 'sortable' => true],
            'preview_description' => ['label' => 'Preview Description', 'sortable' => false],
            'main_description' => ['label' => 'Main Description', 'sortable' => false],
            'iconic_albums' => ['label' => 'Iconic Albums', 'sortable' => false],
            'actions' => ['label' => 'Actions', 'sortable' => false],
        ];
    }

    private function redirectToArtists(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('artists', $success, $message);
    }

    private function showForm(): void
    {
        $_SESSION['show_artist_form'] = true;
        $this->redirectToArtists();
    }
}