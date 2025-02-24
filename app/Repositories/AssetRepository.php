<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Helpers\QueryBuilder;

class AssetRepository extends Repository
{
    public function getAssetById(int $id): ?Asset
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryAsset = $queryBuilder->table('assets')->where('id', '=', $id)->first();

        return $queryAsset ? new Asset($queryAsset) : null;
    }

    public function getAssetsByModel(mixed $model, ?string $collection = null): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryAssets = $queryBuilder->table('assets')
            ->where('model', '=', get_class($model))
            ->where('model_id', '=', $model->id);

        if ($collection) {
            $queryAssets->where('collection', '=', $collection);
        }

        $assets = $queryAssets->get();

        return array_map(function ($asset) {
            return new Asset($asset);
        }, $assets);
    }

    public function assetExists(string $name): bool
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryAsset = $queryBuilder->table('assets')->where('filename', '=', $name)->first();

        return $queryAsset ? true : false;
    }

    public function saveAsset(Asset $asset): Asset
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $assetId = $queryBuilder->table('assets')->insert([
            'collection' => $asset->collection,
            'filename' => $asset->filename,
            'mimetype' => $asset->mimetype,
            'size' => $asset->size,
            'model' => $asset->model,
            'model_id' => $asset->model_id
        ]);
        $asset = $this->getAssetById((int) $assetId);

        return $asset;
    }

    public function deleteAsset(int $assetId): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryBuilder->table('assets')->where('id', '=', $assetId)->delete();
    }
}
