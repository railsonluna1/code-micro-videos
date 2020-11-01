<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestValidations
{
    abstract protected function model();
    abstract protected function routerStore();
    abstract protected function routerUpdate();

    protected function assertInvalidationInStoreAction(
        array $data,
        string $rule,
        array $ruleParams = []
    ) {
        $response = $this->json('POST', $this->routerStore(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFilds($response, $fields, $rule, $ruleParams);
    }

    protected function assertInvalidationInUpdateAction(
        array $data,
        string $rule,
        array $ruleParams = []
    ) {
        $response = $this->json('PUT', $this->routerUpdate(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFilds($response, $fields, $rule, $ruleParams);
    }

    protected function assertInvalidationFilds(
        TestResponse $response,
        array $fields,
        string $rule,
        array $ruleParams = []
    ) {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
               \Lang::get("validation.{$rule}", ['attribute' => $fieldName] + $ruleParams)
            ]);

        }
    }
}
