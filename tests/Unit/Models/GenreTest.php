<?php

namespace Tests\Unit;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class GenreTest extends TestCase
{
    /** @var Genre $genre */
    private $genre;

    protected function setUp()
    {
        parent::setUp();
        $this->genre = new Genre();
    }

    public function testHasTraits()
    {
        $traits = [SoftDeletes::class, Uuid::class];

        $genresTraits = array_keys(class_uses(Genre::class));

        $this->assertEquals($traits, $genresTraits);;
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'is_active'];;

        $this->assertEquals($this->genre->getFillable(), $fillable);
    }

    public function testDatesAttributes()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];

        foreach ($dates as $date) {
            $this->assertContains($date, $this->genre->getDates());
        }
        $this->assertCount(count($dates), $this->genre->getDates());
    }

    public function testCastAttribues()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];

        $this->assertEquals($casts, $this->genre->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->genre->incrementing);
    }
}
