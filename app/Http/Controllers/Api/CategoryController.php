<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;


class CategoryController extends BasicCurdController
{

    protected function model()
    {
        return Category::class;
    }

    protected function rulesStore()
    {
        return (new CategoryRequest())->rules();
    }

    protected function rulesUpdate()
    {
        return (new CategoryRequest())->rules();
    }
}
