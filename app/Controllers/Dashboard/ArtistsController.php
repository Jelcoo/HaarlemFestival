<?php

namespace App\Controllers\Dashboard;

use App\Services\AssetService;
use Rakit\Validation\Validator;
use App\Repositories\ArtistRepository;

class ArtistsController extends DashboardController
{
    private ArtistRepository $artistRepository;
    private AssetService $assetService;

    public function __construct()
    {
        parent::__construct();
        $this->artistRepository = new ArtistRepository();
        $this->assetService = new AssetService();
    }

    public function index(): string
    {
        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToArtists();
        }

        return $this->renderPage(
            'artists',
            [
                'artists' => $this->artistRepository->getSortedArtists($searchQuery, $sortColumn, $sortDirection),
                'status' => $this->getStatus(),
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
                'searchQuery' => $searchQuery,
            ]
        );
    }

    public function deleteArtist(): void
    {
        $artistId = $_POST['id'] ?? null;
        if (!$artistId) $this->redirectToArtists(false, 'Invalid artist ID.');

        $success = (bool) $this->artistRepository->deleteArtist($artistId);
        $this->redirectToArtists($success, $success ? 'Artist deleted successfully.' : 'Failed to delete artist');
    }

    public function editArtist(): string
    {
        $artistId = $_GET['id'] ?? null;
        if (!$artistId) $this->redirectToArtists(false, 'Invalid artist ID.');

        $artist = $this->artistRepository->getArtistById($artistId);
        if (!$artist) $this->redirectToArtists(false, 'Artist not found.');

        $artistCover = $this->assetService->resolveAssets($artist, 'cover');

        $formData = [
            'id' => $artist->id,
            'name' => $artist->name,
            'preview_description' => $artist->preview_description,
            'main_description' => $artist->main_description,
            'iconic_albums' => $artist->iconic_albums,
            'cover' => count($artistCover) > 0 ? $artistCover[0]->getUrl() : null,
        ];

        return $this->showArtistForm('edit', $formData);
    }

    public function editArtistPost(): string
    {
        try {
            $artistId = $_POST['id'] ?? null;
            if (!$artistId) throw new \Exception('Invalid artist ID');

            $existingArtist = $this->artistRepository->getArtistById($artistId);
            if (!$existingArtist) throw new \Exception('Artist not found');

            $validator = new Validator();
            $validation = $validator->validate(
                $_POST + $_FILES,
                [
                    'artist_cover' => 'required|uploaded_file|max:5M|mimes:jpeg,png',
                    'name' => 'required|max:255',
                    'preview_description' => 'nullable|max:500',
                    'main_description' => 'nullable|max:2000',
                    'iconic_albums' => 'nullable|max:1000',
                ]
            );

            if ($validation->fails()) {
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $existingArtist->name = $_POST['name'];
            $existingArtist->preview_description = $_POST['preview_description'] ?? null;
            $existingArtist->main_description = $_POST['main_description'] ?? null;
            $existingArtist->iconic_albums = $_POST['iconic_albums'] ?? null;

            $this->artistRepository->updateArtist($existingArtist);

            $artistAssets = $this->assetService->resolveAssets($existingArtist);
            foreach ($artistAssets as $asset) {
                $this->assetService->deleteAsset($asset);
            }

            $this->assetService->saveAsset($_FILES['artist_cover'], 'cover', $existingArtist);

            $this->redirectToArtists(true, 'Artist updated successfully.');
        } catch (\Exception $e) {
            $this->showArtistForm('edit', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    public function createArtist(): string 
    {
        return $this->showArtistForm();
    }

    public function createArtistPost(): string
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate(
                $_POST + $_FILES,
                [
                    'artist_cover' => 'required|uploaded_file|max:5M|mimes:jpeg,png',
                    'name' => 'required|max:255',
                    'preview_description' => 'nullable|max:500',
                    'main_description' => 'nullable|max:2000',
                    'iconic_albums' => 'nullable|max:1000',
                ]
            );

            if ($validation->fails()) {
                return $this->showArtistForm('create', $_POST, $validation->errors()->all());
            }

            $artistData = array_intersect_key(
                $_POST,
                array_flip(
                    [
                        'name',
                        'preview_description',
                        'main_description',
                        'iconic_albums',
                    ]
                )
            );

            $newArtist = $this->artistRepository->createArtist($artistData);

            $this->assetService->saveAsset($_FILES['artist_cover'], 'cover', $newArtist);

            $this->redirectToArtists(true, 'Artist created successfully.');
        } catch (\Exception $e) {
            return $this->showArtistForm('create', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    private function redirectToArtists(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('artists', $success, $message);
    }

    public function showArtistForm(string $mode = 'create', array $formData = [], array $errors = [], array $status = []): string
    {
        return $this->showForm(
            'artist',
            $mode,
            $formData,
            $errors,
            $status,
        );
    }

    private function exportArtists(): void
    {
        $artists = $this->artistRepository->getAllArtists();

        $columns = [
            'id' => 'ID',
            'name' => 'Name',
            'preview_description' => 'Preview Description',
            'main_description' => 'Main Description',
            'iconic_albums' => 'Iconic Albums',
        ];

        $this->exportToCsv('artists', $artists, $columns);
    }
}
