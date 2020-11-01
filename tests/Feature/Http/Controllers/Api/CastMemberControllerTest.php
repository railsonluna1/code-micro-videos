<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use App\Models\Category;

use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CastMemberControllerTest extends TestCase
{
    use TestValidations, TestSaves;

    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = factory(CastMember::class)->create(
            ['type' => CastMember::TYPE_DIRECTOR]
        );
    }

    public function testIndex()
    {
        $response = $this->get(route('cast_members.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->castMember->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('cast_members.show', ['cast_member' => $this->castMember->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->castMember->toArray());
    }

    public function testStore()
    {
        $data = [
            'name' => 'test',
            'type' => CastMember::TYPE_DIRECTOR
        ];
        $response = $this->assertStore($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
           'created_at', 'updated_at'
        ]);
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'name',
            'type' => CastMember::TYPE_ACTOR
        ];
        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);

    }

    public function testInvalidData()
    {
        $data = ['name' => '', 'type' => ''];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data =  ['type' => 's'];
        $this->assertInvalidationInStoreAction($data, 'in');
        $this->assertInvalidationInUpdateAction($data, 'in');
    }

    public function testDestroy()
    {
        $response = $this->delete(route('cast_members.destroy', ['cast_member' => $this->castMember->id]));
        $response->assertNoContent();
    }

    protected function model()
    {
        return CastMember::class;
    }

    protected function routerStore()
    {
        return route('cast_members.store');
    }

    protected function routerUpdate()
    {
        return route('cast_members.update', ['cast_member' => $this->castMember->id]);
    }
}
