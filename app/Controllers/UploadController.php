<?php

namespace App\Controllers;

use App\Services\AssetService;
use Rakit\Validation\Validator;

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
        $validator = new Validator();
        $validation = $validator->validate($_FILES, [
            'file' => 'required|uploaded_file|max:5M|mimes:jpeg,png',
        ]);

        if ($validation->fails()) {
            return [
                'status' => 400,
                'error' => $validation->errors()->first('file'),
            ];
        }

        try {
            $asset = $this->assetService->saveAsset($_FILES['file'], 'default', null);
        } catch (\Exception) {
            return [
                'status' => 500,
                'error' => 'File upload failed',
            ];
        }

        return [
            'location' => $asset->getUrl(),
        ];
    }
}
