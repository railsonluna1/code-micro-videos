<?php

namespace Tests\Unit;

use App\Models\CastMember;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CastMemberTest extends TestCase
{
    /** @var CastMember $castMember */
    private $castMember;

    protected function setUp()
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }

    public function testHasTraits()
    {
        $traits = [SoftDeletes::class, Uuid::class];

        $castMemberTraits = array_keys(class_uses(CastMember::class));

        $this->assertEquals($traits, $castMemberTraits);;
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'type'];;

        $this->assertEquals($this->castMember->getFillable(), $fillable);
    }

    public function testDatesAttributes()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];

        foreach ($dates as $date) {
            $this->assertContains($date, $this->castMember->getDates());
        }
        $this->assertCount(count($dates), $this->castMember->getDates());
    }

    public function testCastAttribues()
    {
        $casts = ['id' => 'string'];

        $this->assertEquals($casts, $this->castMember->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->castMember->incrementing);
    }
}
