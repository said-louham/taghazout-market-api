<?php

namespace App\Models;

use App\Enums\UploadCollectionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaCollection(name: UploadCollectionEnum::SlIDERS->value)
            ->useDisk('s3')
            ->singleFile();
    }
}
