<?php

namespace App\Services;

class UploadService
{
    public static function getMedia($mediaModel = null, string $collection = null, $media = null, $isDownload = null)
    {

        $media = $mediaModel->getMedia($collection);

        return collect($media)->map(function ($item) {
            $mediaUrl = $item->getUrl();

            return [
                'uuid'  => $item->uuid,
                'media' => $mediaUrl,
            ];
        });

    }

    public static function deleteMedia($relatedModel, string $collection)
    {
        // !!! this will only work for single media collection !!!
        $relatedModel->getFirstMedia($collection)?->delete();
    }
}
