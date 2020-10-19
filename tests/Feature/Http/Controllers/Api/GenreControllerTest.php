<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\TestResponse;

use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    public function testIndex()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('geners.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$genre->toArray()])
        ;

    }

    public function testShow()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('geners.show', ['gener' => $genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray());
    }

    public function testStore()
    {
        $response = $this->json(
            'POST',
            route('geners.store'),
            ['name' => 'teste']
        );
        $this->assertTrue($response->json('is_active'));
        $response->assertStatus(201);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->json(
            'PUT',
            route('geners.update', ['gener' => $genre->id]),
            ['name' => 'teste', 'is_active' => false]
        );

        $response->assertJsonFragment([
            'name' => 'teste', 'is_active' => false
        ]);
        $response->assertStatus(200);
    }

    public function testInvalidPost()
    {
        $response = $this->json('POST', route('geners.store'), []);
        $this->invalidNameRequired($response);

        $response = $this->json(
            'POST', route('geners.store'),
            ['name' => str_repeat('a', 258)]
        );
        $this->invalidNameMax($response);

        $response = $this->json(
            'POST', route('geners.store'),
            ['is_active' => 'a']
        );
        $this->invalidIsActiveBoolean($response);
    }

    public function testDestroy()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->delete(route('geners.destroy', ['gener' => $genre->id]));
        $response->assertNoContent();
    }

    protected function invalidNameRequired(TestResponse $response)
    {
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        $response->assertJsonMissingValidationErrors(['is_active']);
        $response->assertJsonFragment([
            \Lang::get('validation.required', ['attribute' => 'name'])
        ]);
    }

    protected function invalidNameMax(TestResponse $response)
    {
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        $response->assertJsonFragment([
            \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
        ]);
    }

    protected function invalidIsActiveBoolean(TestResponse $response)
    {
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_active']);
        $response->assertJsonFragment([
            \Lang::get('validation.boolean', ['attribute' => 'is active'])
        ]);
    }

}
