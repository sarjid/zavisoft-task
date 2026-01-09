<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\BulkDeleteCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryListResource;
use App\Http\Requests\ChangeCategoryStatusRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $perPage = $request->input('perPage') ?: 8;
        $categories = Category::query()
            ->select(['id', 'name', 'slug', 'image', 'status'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return CategoryListResource::collection($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }

    public function multipleDelete(BulkDeleteCategoryRequest $request)
    {
        $deleted = Category::whereIn('id', $request->validated('ids'))->delete();

        return $this->successResponse([
            'deleted' => $deleted,
        ], 'Categories deleted successfully');
    }

    public function changeStatus(ChangeCategoryStatusRequest $request, Category $category)
    {
        $category->update([
            'status' => $request->validated('status'),
        ]);

        return $this->successResponse([
            'id' => $category->id,
            'status' => $category->status,
        ]);
    }
}
