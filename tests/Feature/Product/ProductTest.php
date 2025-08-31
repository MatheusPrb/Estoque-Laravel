<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private const BASE_URL = '/api/register/products';

    public function createParams(): array
    {
        return  [
            'name' => 'Test Product',
            'price' => 100.00,
            'amount' => 10,
        ];
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $params = $this->createParams();
        $response = $this->post(self::BASE_URL, $params);

        $response
            ->assertStatus(201)
            ->assertJson([
                'name' => 'Test Product',
                'price' => 100.00,
                'amount' => 10
            ])
        ;
    }

    public function test_the_application_validates_required_fields(): void
    {
        $response = $this->post(self::BASE_URL, []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'amount'])
        ;
    }

    public function test_the_application_validates_field_types(): void
    {
        $params = [
            'name' => 123,
            'price' => 'invalid',
            'amount' => 'invalid',
        ];

        $response = $this->post(self::BASE_URL, $params);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'amount'])
        ;
    }
}
