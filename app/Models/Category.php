<?php

namespace App\Models;

use App\Enums\UploadCollectionEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaCollection(name: UploadCollectionEnum::CATEGORIES->value)
            ->useDisk('s3')
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
