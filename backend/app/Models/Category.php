<?php

namespace App\Models;

use App\Enums\StatusEnum;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $casts = [
        'status' => StatusEnum::class,
    ];


    /**
     * Scope a query to only active category.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', StatusEnum::ACTIVE->value);
    }

    /**
     * Scope a query to only Inactive category.
     */
    public function scopeInActive(Builder $query): void
    {
        $query->where('status', StatusEnum::INACTIVE->value);
    }
}
