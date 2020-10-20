<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use TestValidations;

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
        $data = ['name' => ''];
        $this->assertInvalidationInStoreAction($data, 'required');

        $data = ['name' => str_repeat('a', 258)];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);

        $data =  ['is_active' => 'a'];
        $this->assertInvalidationInStoreAction($data, 'boolean');
    }

    public function testDestroy()
    {
        $category = factory(Category::class)->create();
        $response = $this->delete(route('categories.destroy', ['category' => $category->id]));
        $response->assertNoContent();
    }

    protected function invalidNameRequired(TestResponse $response)
    {
        $this->assertInvalidationFilds($response, ['name'], 'required');
        $response->assertJsonMissingValidationErrors(['is_active', 'description']);
    }

    protected function invalidNameMax(TestResponse $response)
    {
        $this->assertInvalidationFilds($response, ['name'], 'max.string', ['max' => 255]);
    }

    protected function invalidIsActiveBoolean(TestResponse $response)
    {
        $this->assertInvalidationFilds($response, ['is_active'], 'boolean');
    }

    protected function routerStore()
    {
        return route('categories.store');
    }
}
