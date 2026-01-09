<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeValue extends Model
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the attribute that owns the AttributeValue
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
