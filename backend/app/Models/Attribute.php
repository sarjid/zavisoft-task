<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
   /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];


    /**
     * Get all of the values for the Attribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

}
