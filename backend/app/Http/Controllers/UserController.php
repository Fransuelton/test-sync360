<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json([
            'sucess' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'profile_image' => 'required|string',
                'full_name' => 'required|string',
                'age' => 'required|integer',
                'street' => 'required|string',
                'neighborhood' => 'required|string',
                'state' => 'required|string',
                'biography' => 'required|string',
            ]

        );

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'data' => $user
        ], 201);
    }


    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate(
            [
                'profile_image' => 'string',
                'full_name' => 'string',
                'age' => 'integer',
                'street' => 'string',
                'neighborhood' => 'string',
                'state' => 'string',
                'biography' => 'string',
            ]

        );

        $user = User::findOrFail($id);
        $user->update($validated);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }


    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
