<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;


class CategoryController extends Controller
{

    public function index()
    {
        return Category::all();
    }

    public function store(CategoryRequest $request)
    {
        /** @var Category $category */
        $category = Category::create($request->all());
        $category->refresh();

        return $category;
    }

    public function show(Category $category)
    {
        return $category;
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->fill($request->all());
        $category->update();
        $category->refresh();

        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
