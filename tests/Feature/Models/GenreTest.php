<?php

namespace Tests\Feature\Models;


use App\Models\Genre;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;

class GenreTest extends TestCase
{

    public function testList()
    {
        factory(Genre::class, 1)->create();
        $geners = Genre::all();

        self::assertCount(1, $geners);

        $genreKeys = array_keys($geners->first()->getAttributes());
        self::assertEqualsCanonicalizing(
            ['id', 'name', 'is_active', 'created_at', 'updated_at', 'deleted_at'],
            $genreKeys
        );
    }

    public function testCreate()
    {
        $genre = Genre::create([
            'name' => 'teste'
        ]);
        $genre->refresh();
        self::assertEquals('teste', $genre->name);
        self::assertTrue($genre->is_active);
        self::assertTrue(Uuid::isValid($genre->id));

        $genre = Genre::create([
            'name' => 'teste',
            'is_active' => false
        ]);
        self::assertFalse($genre->is_active);
    }

    public function testUpdate()
    {
        /** @var Genre $genre */
        $genre = Genre::create([
            'name' => 'teste',
        ]);

        $data = [
            'name' => 'new name',
            'is_active' => false
        ];
        $genre->update($data);

        foreach ($data as $key => $value) {
            self::assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        /** @var Genre $genre */
        $genre = factory(Genre::class)->create();
        $genre->delete();

        self::assertCount(0, $genre->all());
        self::assertTrue($genre->trashed());

    }
}
