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

    public function show(int $id): string
    {
        $artist = $this->artistRepository->getArtistById($id);
        if (!$artist) {
            return $this->pageLoader->setPage('_404')->render();
        }

        // Fetch artist schedule from DanceRepository
        $schedule = $this->danceRepository->getScheduleByArtistId($id);

        // Split iconic_albums into an array
        $albums = explode("\r\n", $artist->iconic_albums);

        return $this->pageLoader->setPage('artist-detail')->render([
            'artist' => $artist,
            'schedule' => $schedule,
            'albums' => $albums,
            'placeholder_image' => '/assets/img/artists/placeholder-artist.png'
        ]);
    }
}