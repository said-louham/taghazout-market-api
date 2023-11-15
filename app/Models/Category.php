<?php

namespace App\Models;

use App\Enums\UploadCollectionEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        //  'image', add media
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaCollection(name: UploadCollectionEnum::CATEGORIES->value)
            ->useDisk('s3')
        //    ->fit(Manipulations::FIT_CROP, 300, 300)
        //  ->nonQueued()
            ->singleFile();

    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
