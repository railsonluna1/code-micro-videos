<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;

use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use TestValidations, TestSaves;

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = factory(Category::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->category->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('categories.show', ['category' => $this->category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->category->toArray());
    }

    public function testStore()
    {
        $data = [
            'name' => 'test'
        ];
        $response = $this->assertStore($data, $data + ['description' => null, 'is_active' => true, 'deleted_at' => null]);
        $response->assertJsonStructure([
           'created_at', 'updated_at'
        ]);

        $data = [
            'name' => 'teste',
            'description' => 'description',
            'is_active' => false
        ];
        $this->assertStore($data, $data + ['name' => 'teste', 'description' => 'description', 'is_active' => false, 'deleted_at' => null]);
    }

    public function testUpdate()
    {
        $this->category = factory(Category::class)->create([
            'description' => 'description',
            'is_active' => true
        ]);
        $data = [
            'name' => 'teste',
            'description' => 'description',
            'is_active' => true
        ];
        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);

        $data = [
            'name' => 'teste',
            'description' => ''
        ];
        $this->assertUpdate($data, array_merge($data, ['description' => null]) );

    }

    public function testInvalidPost()
    {
        $data = ['name' => ''];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = ['name' => str_repeat('a', 258)];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);

        $data =  ['is_active' => 'a'];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    public function testDestroy()
    {
        $response = $this->delete(route('categories.destroy', ['category' => $this->category->id]));
        $response->assertNoContent();
    }

    protected function model()
    {
        return Category::class;
    }

    protected function routerStore()
    {
        return route('categories.store');
    }

    protected function routerUpdate()
    {
        return route('categories.update', ['category' => $this->category->id]);
    }
}
