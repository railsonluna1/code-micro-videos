<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;

use Illuminate\Foundation\Testing\TestResponse;

use Tests\TestCase;

class CategoryControllerTest extends TestCase
{

    public function testIndex()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testShow()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show', ['category' => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testStore()
    {
        $response = $this->json(
            'POST',
            route('categories.store'),
            ['name' => 'teste']
        );
        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));
        $response->assertStatus(201);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create();
        $response = $this->json(
            'PUT',
            route('categories.update', ['category' => $category->id]),
            ['name' => 'teste', 'description' => 'description', 'is_active' => false]
        );

        $response->assertJsonFragment([
            'name' => 'teste', 'description' => 'description', 'is_active' => false
        ]);
        $response->assertStatus(200);
    }

    public function testInvalidPost()
    {
        $response = $this->json('POST', route('categories.store'), []);
        $this->invalidNameRequired($response);

        $response = $this->json(
            'POST', route('categories.store'),
            ['name' => str_repeat('a', 258)]
        );
        $this->invalidNameMax($response);

        $response = $this->json(
            'POST', route('categories.store'),
            ['is_active' => 'a']
        );
        $this->invalidIsActiveBoolean($response);
    }

    public function testDestroy()
    {
        $category = factory(Category::class)->create();
        $response = $this->delete(route('categories.destroy', ['category' => $category->id]));
        $response->assertNoContent();
    }

    protected function invalidNameRequired(TestResponse $response)
    {
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        $response->assertJsonMissingValidationErrors(['is_active', 'description']);
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
