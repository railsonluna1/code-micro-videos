<?php

namespace  Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCurdController;
use Tests\Stubs\Models\CategoryStub;

class CategoryControllerStubs extends BasicCurdController
{
    public function index()
    {
        return $this->model()::all();
    }

    protected function model()
    {
        return CategoryStub::class;
    }

    protected function rulesStore()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable'
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable'
        ];
    }
}
