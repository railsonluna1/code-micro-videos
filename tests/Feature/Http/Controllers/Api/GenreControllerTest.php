<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\TestResponse;

use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class GenreControllerTest extends TestCase
{
    use TestValidations, TestSaves;

    private $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = factory(Genre::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('geners.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->genre->toArray()])
        ;

    }

    public function testShow()
    {
        $response = $this->get(route('geners.show', ['gener' => $this->genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->genre->toArray());
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
        $this->assertInvalidationFilds($response, ['name'], 'required');
        $response->assertJsonMissingValidationErrors(['is_active']);
    }

    protected function invalidNameMax(TestResponse $response)
    {
        $this->assertInvalidationFilds($response, ['name'], 'max.string', ['max' => 255]);
    }

    protected function invalidIsActiveBoolean(TestResponse $response)
    {
        $this->assertInvalidationFilds($response, ['is_active'], 'boolean');
    }


    protected function model()
    {
        return Genre::class;
    }

    protected function routerStore()
    {
        return route('geners.store.store');
    }

    protected function routerUpdate()
    {
        return route('geners.store.update', ['gener' => $this->ge->id]);
    }
}
