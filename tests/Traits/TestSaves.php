<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{
    abstract protected function model();
    abstract protected function routerStore();
    abstract protected function routerUpdate();

    protected function assertStore(array $sendData, array $testDatabase, $testJsonData = null): TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->routerStore(), $sendData);

        if ($response->status() !== 201) {
            throw new \Exception("Response status must be 201, given {$response->status()}: \n{$response->content()}");
        }
        $this->assertInDataBase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);

        return $response;
    }

    protected function assertUpdate(array $sendData, array $testDatabase, $testJsonData = null): TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('PUT', $this->routerUpdate(), $sendData);

        if ($response->status() !== 200) {
            throw new \Exception("Response status must be 200, given {$response->status()}: \n{$response->content()}");
        }
        $this->assertInDataBase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);

        return $response;
    }

    private function assertInDataBase(TestResponse $response, array $testDatabase)
    {
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + ['id' => $response->json('id')]);
    }

    private function assertJsonResponseContent(TestResponse $response, array $testDatabase, array $testJsonData = null)
    {
        $testResponse = $testDatabase ?? $testJsonData;
        $response->assertJsonFragment($testResponse + ['id' => $response->json('id')]);
    }
}
