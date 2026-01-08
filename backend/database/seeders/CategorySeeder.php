<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Men Fashion',
            'Women Fashion Item',
            'Electronics',
            'Food Items',
            'Kitchen Essentials',
            'Home Decor',
            'Books & Stationery',
            'Kids Toys',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'image' => null,
                'status' => StatusEnum::ACTIVE->value,
            ]);
        }
    }
}
