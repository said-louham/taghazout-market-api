<?php

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class CustomPathGenerator extends DefaultPathGenerator
{
    /*
     Get a unique base path for the given media.
    */
    protected function getBasePath(Media $media): string
    {
        $model = explode('\\', $media->model_type);

        return end($model).'/'.$media->getKey();
    }
}
