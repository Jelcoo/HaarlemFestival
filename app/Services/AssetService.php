<?php

namespace App\Services;

use App\Models\Asset;
use App\Repositories\AssetRepository;

class AssetService
{
    private AssetRepository $assetRepository;
    private FileService $fileService;

    public function __construct()
    {
        $this->assetRepository = new AssetRepository();
        $this->fileService = new FileService();
    }

    public function resolveAssets(mixed $model, ?string $collection = null): array
    {
        $assets = $this->assetRepository->getAssetsByModel($model, $collection);

        return $assets;
    }

    public function saveBase64Asset(string $asset, string $collection, mixed $model): Asset
    {
        $mimeType = mime_content_type($asset);
        $fileName = $this->generateUuid() . '.' . FileService::getExtension($mimeType);

        $savedFile = $this->fileService->saveBase64File($asset, $this->fileService->getFilePath($fileName));

        if (!$savedFile) {
            throw new \Exception('Failed to save file');
        }

        $asset = new Asset();
        $asset->collection = $collection;
        $asset->filename = $fileName;
        $asset->mimetype = $mimeType;
        $asset->size = filesize($savedFile);
        $asset->model = get_class($model);
        $asset->model_id = $model->id;

        return $this->assetRepository->saveAsset($asset);
    }

    public function deleteAsset(Asset $asset): void
    {
        $this->fileService->deleteFile($this->fileService->getFilePath($asset->filename));

        $this->assetRepository->deleteAsset($asset->id);
    }

    private function generateUuid(): string
    {
        $uuid = bin2hex(random_bytes(16));
        $existingAsset = $this->assetRepository->assetExists($uuid);

        if ($existingAsset) {
            return $this->generateUuid();
        }

        return $uuid;
    }
}
