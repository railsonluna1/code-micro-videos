<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class VideoController extends BasicCurdController
{
    private $rules;

    /**
     * VideoController constructor.
     */
    public function __construct()
    {
        $this->rules = [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => 'required|in:'. implode(',', Video::RATING_LIST),
            'duration' => 'required|integer',
            'categories_id' => 'required|array|exists:categories,id',
            'genres_id' => 'required|array|exists:genres,id',
        ];
    }

    /**
     * @param Request $request
     * @return Video
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $self = $this;
        $validatedData = $this->validate($request, $this->rulesStore());

        /** @var Video $video */
        $video = DB::transaction(function () use ($validatedData, $request, $self) {
            $video = Video::create($validatedData);
            $self->handleRelations($video, $request);

            return $video;
        });
        $video->refresh();

        return $video;
    }

    /**
     * @param Request $request
     * @param $id
     * @return Video|mixed
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        /** @var Video $video */
        $video = $this->firstOrFail($id);
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $self = $this;

        $video = DB::transaction(function () use ($validatedData, $request, $self, $video) {
            $video->update($validatedData);
            $self->handleRelations($video, $request);
            return $video;
        });
        $video->refresh();

        return $video;
    }

    protected function handleRelations(Video $video, Request $request): void
    {
        $video->categories()->sync($request->get('categories_id'));
        $video->genres()->sync($request->get('genres_id'));
    }

    protected function model()
    {
        return Video::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }
}
