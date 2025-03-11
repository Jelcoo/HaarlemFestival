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

    /*
     * Returns an array of assets for a given model.
     *
     * @param mixed $model
     * @param ?string $collection The collection name of the assets to be returned.
     */
    public function resolveAssets(mixed $model, ?string $collection = null): array
    {
        $assets = $this->assetRepository->getAssetsByModel($model, $collection);

        return $assets;
    }

    /*
     * Saves an asset to the filesystem and database.
     *
     * @param array $file The file object from the request $_FILES.
     * @param string $collection The collection name the asset should belong to.
     * @param mixed $model The model the asset belongs to.
     *
     * @return Asset
     * @throws \Exception
     */
    public function saveAsset(array $file, string $collection, mixed $model): Asset
    {
        if (isset($file['error']) && $file['error'] !== 0) {
            throw new \Exception('Failed to upload file');
        }

        $mimeType = $this->getMimeType($file['tmp_name']);
        $filePath = $this->fileService->getFilePath();
        $fileName = $this->generateUuid() . '.' . FileService::getExtension($mimeType);

        $savedFile = $this->fileService->saveFile($file, $this->fileService->assembleFilePath($filePath, $fileName));

        if (!$savedFile) {
            throw new \Exception('Failed to save file');
        }

        $asset = new Asset();
        $asset->collection = $collection;
        $asset->filepath = $filePath;
        $asset->filename = $fileName;
        $asset->mimetype = $mimeType;
        $asset->size = $file['size'];
        $asset->model = get_class($model);
        $asset->model_id = $model->id;

        return $this->assetRepository->saveAsset($asset);
    }

    /*
     * Deletes a given asset from the filesystem and database.
     */
    public function deleteAsset(Asset $asset): void
    {
        $this->fileService->deleteFile($this->fileService->assembleFilePath($asset->filepath, $asset->filename));

        $this->assetRepository->deleteAsset($asset->id);
    }

    /*
     * Generate a unique UUID for the asset name.
     */
    private function generateUuid(): string
    {
        $uuid = bin2hex(random_bytes(16));
        $existingAsset = $this->assetRepository->assetExists($uuid);

        if ($existingAsset) {
            return $this->generateUuid();
        }

        return $uuid;
    }

    private function getMimeType(string $filePath): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        return $mimeType;
    }
}
