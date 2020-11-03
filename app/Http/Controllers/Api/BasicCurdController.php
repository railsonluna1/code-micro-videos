<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class BasicCurdController extends Controller
{
    abstract protected function model();
    abstract protected function rulesStore();
    abstract protected function rulesUpdate();

    public function index()
    {
        return $this->model()::all();
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());
        $object = $this->model()::create($validatedData);
        $object->refresh();

        return $object;
    }

    protected function firstOrFail($id)
    {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();

        return $this->model()::where($keyName, $id)->firstOrFail();
    }

    public function show($id)
    {
        return $this->firstOrFail($id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $object = $this->firstOrFail($id);
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $object->update($validatedData);
        $object->refresh();

        return $object;
    }

    public function destroy($id)
    {
        $object = $this->firstOrFail($id);
        $object->delete();

        return response()->noContent();
    }
}
