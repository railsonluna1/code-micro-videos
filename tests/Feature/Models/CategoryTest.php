<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;

class CategoryTest extends TestCase
{

    public function testList()
    {
        factory(Category::class, 1)->create();
        $categories = Category::all();

        self::assertCount(1, $categories);

        $categoryKeys = array_keys($categories->first()->getAttributes());
        self::assertEqualsCanonicalizing(
            ['id', 'name', 'description', 'is_active', 'created_at', 'updated_at', 'deleted_at'],
            $categoryKeys
        );
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'teste'
        ]);
        $category->refresh();
        self::assertEquals('teste', $category->name);
        self::assertNull($category->description);
        self::assertTrue($category->is_active);
        self::assertTrue(Uuid::isValid($category->id));

        $category = Category::create([
            'name' => 'teste',
            'description' => null
        ]);
        self::assertNull($category->description);

        $category = Category::create([
            'name' => 'teste',
            'description' => 'teste_description'
        ]);
        self::assertEquals('teste_description', $category->description);


        $category = Category::create([
            'name' => 'teste',
            'is_active' => false
        ]);
        self::assertFalse($category->is_active);
    }

    public function testUpdate()
    {
        /** @var Category $category */
        $category = Category::create([
            'name' => 'teste',
            'description' => 'description_teste'
        ]);

        $data = [
            'name' => 'new name',
            'description' => 'new description',
            'is_active' => false
        ];
        $category->update($data);

        foreach ($data as $key => $value) {
            self::assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();
        $category->delete();

        self::assertCount(0, $category->all());
        self::assertTrue($category->trashed());

    }
}
