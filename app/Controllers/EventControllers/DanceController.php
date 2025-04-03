<?php

namespace App\Controllers\EventControllers;

use App\Controllers\Controller;
use App\Repositories\ArtistRepository;
use App\Repositories\DanceRepository;
use App\Repositories\LocationRepository;
use App\Services\ScheduleService;
use App\Services\AssetService;

class DanceController extends Controller
{
    private ArtistRepository $artistRepository;
    private DanceRepository $danceRepository;
    private LocationRepository $locationRepository;
    private ScheduleService $scheduleService;
    private AssetService $assetService;

    public function __construct()
    {
        parent::__construct();
        $this->artistRepository = new ArtistRepository();
        $this->danceRepository = new DanceRepository();
        $this->locationRepository = new LocationRepository();
        $this->scheduleService = new ScheduleService();
        $this->assetService = new AssetService();
    }

    public function showDetail(string $slug, int $id): string
    {
        $artist = $this->artistRepository->getArtistById($id);
        if (!$artist) {
            return $this->pageLoader->setPage('_404')->render();
        }
    
        $expectedSlug = str_replace(' ', '_', $artist->name);
        if (urldecode($slug) !== $expectedSlug) {
            header("Location: /dance/{$expectedSlug}_{$id}", true, 301);
            exit;
        }
    
        $schedule = $this->danceRepository->getScheduleByArtistId($id);
        $albums = explode("\r\n", $artist->iconic_albums ?? '');
    
        $headerAsset = $this->assetService->resolveAssets($artist, 'header');
        $extraAssets = $this->assetService->resolveAssets($artist, 'extra');
        $albumAssets = $this->assetService->resolveAssets($artist, 'album');

    
        return $this->pageLoader->setPage('artist-detail')->render([
            'artist' => $artist,
            'schedule' => $schedule,
            'albums' => $albums,
            'headerAsset' => $headerAsset,
            'extraAssets' => $extraAssets,
            'albumAssets' => $albumAssets
        ]);
    }
    
    
    public function showMain(): string
    {
        $artists = $this->artistRepository->getAllArtists();
        $locations = $this->locationRepository->getSpecificLocations('dance');
        $schedules = $this->scheduleService->getDanceSchedule();

        return $this->pageLoader->setPage('dance')->render([
            'artists' => $artists,
            'locations' => $locations,
            'schedules' => $schedules,
        ]);
    }
}
