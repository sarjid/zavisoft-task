<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Helpers\ProductHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'images' => 'array',
        'status' => StatusEnum::class,
    ];


    // calculate current_price
    protected $appends = ['current_price',  'stock_status'];
    public function getCurrentPriceAttribute()
    {
        return ProductHelper::calculateCurrentPrice($this->unit_price, $this->discount_type, $this->discount);
    }
    // calculate current_price


    public function getStockStatusAttribute()
    {
        return ProductHelper::getStockStatus($this->current_stock);
    }

    /**
     * Scope a query to only active products.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status',  StatusEnum::ACTIVE);
    }

    /**
     * Get all of the variants for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }




    /**
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }



    public function attributes()
    {
        return $this->hasManyThrough(
            ProductVariationAttribute::class,
            ProductVariation::class,
            'product_id', // Foreign key on ProductVariant table
            'product_variation_id', // Foreign key on ProductVariantAttribute table
            'id', // Local key on Product table
            'id' // Local key on ProductVariant table
        );
    }
}
