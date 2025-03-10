<?php

namespace App\Controllers;

use App\Services\AssetService;

class UploadController extends Controller
{
    private AssetService $assetService;

    public function __construct()
    {
        parent::__construct();

        $this->assetService = new AssetService();
    }

    public function index(array $parameters = []): array
    {
        try {
            $asset = $this->assetService->saveAsset($_FILES['file'], 'default', null);
        } catch (\Exception $e) {
            return [
                'status' => 500,
                'error' => $e->getMessage(),
            ];
        }

        return [
            'location' => $asset->getUrl(),
        ];
    }
}
