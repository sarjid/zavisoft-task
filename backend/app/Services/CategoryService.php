<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryService
{
    public function create(StoreCategoryRequest $request): Category
    {
        $data = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = file_upload($request->file('image'), 'uploads/categories', 'category');
        }

        return Category::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'image' => $imagePath,
            'status' => $data['status'] ?? StatusEnum::INACTIVE->value,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): Category
    {
        $data = $request->validated();

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            delete_public_file($category->image);
            $imagePath = file_upload($request->file('image'), 'uploads/categories', 'category');
        }

        $category->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'image' => $imagePath,
            'status' => $data['status'] ?? $category->status,
        ]);

        return $category;
    }

    public function deleteImages(iterable $categories): void
    {
        foreach ($categories as $category) {
            delete_public_file($category->image);
        }
    }
}
