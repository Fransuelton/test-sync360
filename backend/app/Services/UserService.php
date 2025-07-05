<?php

namespace App\Services;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Database\Eloquent\Collection;

/**
 * This pattern separates business logic from controllers, creating an intermediate layer responsible for domain-specific operations.
 */

class UserService
{
    /**
     * Get all users
     */
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * Create a new user
     */
    public function createUser(StoreUserRequest $request): User
    {
        return User::create($request->validated());
    }

    /**
     * Find user by ID
     */
    public function findUser(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Update user
     */
    public function updateUser(UpdateUserRequest $request, int $id): User
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return $user->fresh();
    }

    /**
     * Delete user
     */
    public function deleteUser(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
