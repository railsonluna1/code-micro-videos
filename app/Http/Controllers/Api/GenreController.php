<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\GenreRquest;
use App\Models\Category;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{

    public function index()
    {
        return Genre::all();
    }

    public function store(GenreRquest $request)
    {
        return Genre::create($request->all());
    }

    public function show(Genre $gener)
    {
        return $gener;
    }

    public function update(GenreRquest $request, Genre $gener)
    {
        $gener->fill($request->all());
        $gener->update();

        return $gener;
    }

    public function destroy(Genre $gener)
    {
        $gener->delete();

        return response()->noContent();
    }
}
