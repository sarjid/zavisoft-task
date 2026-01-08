<?php

namespace App\Enums;

enum StatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function color(): string
    {
        return match ($this) {
            StatusEnum::ACTIVE => 'success',
            StatusEnum::INACTIVE => 'danger',
        };
    }
    public function label(): string
    {
        return match ($this) {
            StatusEnum::ACTIVE => 'Active',
            StatusEnum::INACTIVE => 'Inactive',
        };
    }
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
