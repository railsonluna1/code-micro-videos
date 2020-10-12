<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\GerneRquest;
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

    public function store(GerneRquest $request)
    {
        return Genre::create($request->all());
    }

    public function show(Genre $gener)
    {
        return $gener;
    }

    public function update(GerneRquest $request, Genre $gener)
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
