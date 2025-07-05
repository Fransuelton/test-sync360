<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $response = $this->postJson('/api/users', [
            'profile_image' => 'https://example.com/image.jpg',
            'full_name' => 'John Doe',
            'age' => 30,
            'street' => '123 Main St',
            'neighborhood' => 'Downtown',
            'state' => 'CA',
            'biography' => 'A brief biography of John Doe.',
        ]);

        $response->assertStatus(201)->assertJson([
            'success' => true,
            'message' => 'User created successfully.',
        ]);
    }

    public function test_all_users_can_be_retrieved(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Users retrieved successfully.'
        ]);
    }

    public function test_user_can_be_retrieved(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'User retrieved successfully.'
        ]);
    }

    public function test_user_can_be_updated(): void
    {
        $user = User::factory()->create();

        $updatedData = [
            'profile_image' => 'https://example.com/new-image.jpg',
            'full_name' => 'Jane Doe',
            'age' => 28,
            'street' => '456 Main St',
            'neighborhood' => 'Uptown',
            'state' => 'NY',
            'biography' => 'A brief biography of Jane Doe.',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updatedData);

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'User updated successfully.'
        ]);
    }

    public function test_user_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
