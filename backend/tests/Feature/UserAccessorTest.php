<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAccessorTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_address_accessor_works(): void
    {
        // Create a user
        $user = User::factory()->create([
            'street' => 'Rua das Flores, 123',
            'neighborhood' => 'Centro',
            'state' => 'SP'
        ]);

        // Test if the accessor works
        $expectedAddress = 'Rua das Flores, 123, Centro, SP';
        $this->assertEquals($expectedAddress, $user->full_address);

        // Check if it appears in JSON
        $this->assertArrayHasKey('full_address', $user->toArray());
    }

    public function test_accessor_in_api_response(): void
    {
        // Create a user
        $user = User::factory()->create([
            'street' => 'Rua das Flores, 123',
            'neighborhood' => 'Centro', 
            'state' => 'SP'
        ]);

        // Make request to fetch the user
        $response = $this->getJson("/api/users/{$user->id}");

        // Verify if accessor appears in response
        $response->assertStatus(200)
                ->assertJsonPath('data.full_address', 'Rua das Flores, 123, Centro, SP');
    }
}
