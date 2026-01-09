<?php

namespace App\Models;

use App\Helpers\ProductHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * Get the product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    /**
     * Get all of the attributes for the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(ProductVariationAttribute::class, 'product_variation_id');
    }


    protected $appends = ['current_price', 'stock_status'];

    public function getCurrentPriceAttribute()
    {
        return ProductHelper::calculateCurrentPrice($this->price, $this->product->discount_type, $this->product->discount);
    }

    public function getStockStatusAttribute()
    {
        return ProductHelper::getStockStatus($this->quantity);
    }
}
