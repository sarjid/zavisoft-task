<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariationAttribute extends Model
{


    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];
    /**
     * Get the user that owns the ProductVariantAttribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }


    /**
     * Get the attribute that owns the ProductVariantAttribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    /**
     * Get the attributeValue that owns the ProductVariantAttribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
