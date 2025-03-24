<?php

namespace App\Controllers;

use App\Repositories\ArtistRepository;
use App\Repositories\DanceRepository;
use App\Services\ScheduleService;

class ArtistController extends Controller
{
    private ArtistRepository $artistRepository;
    private DanceRepository $danceRepository;
    private ScheduleService $scheduleService;

    public function __construct()
    {
        parent::__construct();
        $this->artistRepository = new ArtistRepository();
        $this->danceRepository = new DanceRepository();
        $this->scheduleService = new ScheduleService();
    }

    public function show(string $slug, int $id): string
    {
        $artist = $this->artistRepository->getArtistById($id);
        if (!$artist) {
            return $this->pageLoader->setPage('_404')->render();
        }
    
        // Expected slug based on name (does not handle special characters)
        $expectedSlug = str_replace(' ', '_', $artist->name);
    
        // Redirect if incorrect URL
        if (urldecode($slug) !== $expectedSlug) {
            header("Location: /dance/{$expectedSlug}_{$id}", true, 301);
            exit;
        }
    
        // Fetch artist schedule
        $schedule = $this->danceRepository->getScheduleByArtistId($id);
        $albums = explode("\r\n", $artist->iconic_albums);
    
        return $this->pageLoader->setPage('artist-detail')->render([
            'artist' => $artist,
            'schedule' => $schedule,
            'albums' => $albums,
            'placeholder_image' => '/assets/img/artists/placeholder-artist.png'
        ]);
    }    
}