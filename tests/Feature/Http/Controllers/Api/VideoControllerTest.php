<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\VideoController;
use App\Models\Category;

use App\Models\Genre;
use App\Models\Video;
use Illuminate\Http\Request;
use Tests\TestCase;
use Tests\TestException;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class VideoControllerTest extends TestCase
{
    use TestValidations, TestSaves;

    private $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = factory(Video::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('videos.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->video->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('videos.show', ['video' => $this->video->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->video->toArray());
    }

    public function testInvalidRequired()
    {
        $data = [
            'title' => '',
            'description' => '',
            'year_launched' => '',
            'rating' => '',
            'duration' => ''
        ];

        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');
    }

    public function testInvalidMax()
    {
        $data = ['title' => str_repeat('a', 258)];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);
    }

    public function testInvalidInteger()
    {
        $data = ['duration' => 's'];
        $this->assertInvalidationInStoreAction($data, 'integer');
        $this->assertInvalidationInUpdateAction($data, 'integer');
    }

    public function testInvalidYearLaunchedField()
    {
        $data = ['year_launched' => 's'];
        $this->assertInvalidationInStoreAction($data, 'date_format', ['format' => 'Y']);
        $this->assertInvalidationInUpdateAction($data, 'date_format' , ['format' => 'Y']);
    }

    public function testInvalidBoolean()
    {
        $data = ['opened' => 's'];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    public function testInvalidRatingField()
    {
        $data = ['rating' => 0];
        $this->assertInvalidationInStoreAction($data, 'in');
        $this->assertInvalidationInUpdateAction($data, 'in');
    }

    public function testRollbackStore()
    {
        $sendData = [
            'title' => 'titletitle',
            'description' => 'description description',
            'year_launched' => 2020,
            'rating' => Video::RATING_LIST[1],
            'duration' => 10
        ];
        $controller = \Mockery::mock(VideoController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn($sendData)
        ;

        $controller->shouldReceive('rulesStore')
            ->once()
            ->andReturn([]);

        $request = \Mockery::mock(Request::class);

        $this->expectException(TestException::class);
        $controller->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestException());

        $controller->store($request);

        $this->assertCount(Video::all(), 1);
    }

    public function testStore()
    {
        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();

        $sendData = [
            'title' => 'titletitle',
            'description' => 'description description',
            'year_launched' => 2020,
            'rating' => Video::RATING_LIST[1],
            'duration' => 10
        ];
        $data = [
            'send_data' => $sendData + [
                'categories_id' => [$category->id],
                'genres_id' => [$genre->id]
            ],
            'test_data' => $sendData
        ];

        foreach ($data as $key => $value) {
            $response = $this->assertStore(
                $data['send_data'],
                $data['test_data']
            );
            $response->assertJsonStructure([
                'created_at', 'updated_at'
            ]);
        }
        $response->assertCreated();


    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();

        $sendData = [
            'title' => 'titletitle',
            'description' => 'description description',
            'year_launched' => 2020,
            'rating' => Video::RATING_LIST[1],
            'duration' => 10
        ];
        $data = [
            'send_data' => $sendData + [
                    'categories_id' => [$category->id],
                    'genres_id' => [$genre->id]
                ],
            'test_data' => $sendData
        ];


        foreach ($data as $key => $value) {
            $response = $this->assertUpdate(
                $data['send_data'],
                $data['test_data']
            );
            $response->assertJsonStructure([
                'created_at', 'updated_at'
            ]);
        }
        $response->assertOk();
    }


    public function testDestroy()
    {
        $response = $this->delete(route('videos.destroy', ['video' => $this->video->id]));
        $response->assertNoContent();
    }

    protected function model()
    {
        return Video::class;
    }

    protected function routerStore()
    {
        return route('videos.store');
    }

    protected function routerUpdate()
    {
        return route('videos.update', ['video' => $this->video->id]);
    }
}
