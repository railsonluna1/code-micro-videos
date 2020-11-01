<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\GenreRquest;
use App\Models\Genre;


class GenreController extends BasicCurdController
{

    protected function model()
    {
        return Genre::class;
    }

    protected function rulesStore()
    {
        return (new GenreRquest())->rules();
    }

    protected function rulesUpdate()
    {
        return (new GenreRquest())->rules();
    }
}
