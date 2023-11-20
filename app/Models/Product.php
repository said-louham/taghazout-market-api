<?php

namespace App\Models;

use App\Enums\UploadCollectionEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,Sluggable;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'original_price',
        'selling_price',
        'quantity',
        'trending',
        'featured',
    ];

    protected $casts = [
        'trending'       => 'integer',
        'featured'       => 'integer',
        'original_price' => 'float',
        'selling_price'  => 'float',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaCollection(name: UploadCollectionEnum::PRODUCTS->value)
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'product_id', 'id');
    }
}
