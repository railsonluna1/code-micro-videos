<?php

namespace Tests\Unit;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use App\Models\Video;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    /** @var Video $video */
    private $video;

    protected function setUp()
    {
        parent::setUp();
        $this->video = new Video();
    }

    public function testHasTraits()
    {
        $traits = [SoftDeletes::class, Uuid::class];

        $videoTraits = array_keys(class_uses(Video::class));

        $this->assertEquals($traits, $videoTraits);
    }

    public function testFillableAttribute()
    {
        $fillable = ['title', 'description', 'year_launched', 'duration', 'opened', 'rating'];

        $this->assertEquals($this->video->getFillable(), $fillable);
    }

    public function testDatesAttributes()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];

        foreach ($dates as $date) {
            $this->assertContains($date, $this->video->getDates());
        }
        $this->assertCount(count($dates), $this->video->getDates());
    }

    public function testCastAttribues()
    {
        $casts = [
            'id' => 'string',
            'opened' => 'boolean',
            'year_launched' => 'integer',
            'duration' => 'integer'
        ];

        $this->assertEquals($casts, $this->video->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->video->incrementing);
    }
}
