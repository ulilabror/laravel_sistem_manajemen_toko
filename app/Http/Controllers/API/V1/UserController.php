<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{

    // Get user details including related data
    public function show($id)
    {
        try {
            $user = User::with(['role', 'points', 'files', 'transactions'])->findOrFail($id);
            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // Get all users
    public function index()
    {
        $users = User::with(['role', 'points', 'files', 'transactions'])->paginate(10);
        return UserResource::collection($users);
    }

    // Create a new user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'email', 'password', 'role_id']);
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return new UserResource($user);
    }

    // Update user details
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|required|string|min:6',
                'role_id' => 'sometimes|required|exists:roles,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            $data = $request->only(['name', 'email', 'password', 'role_id']);
            if ($request->has('password')) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // Delete a user
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'User deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // Get the points of a user
    public function points($id)
    {
        try {
            $user = User::with('points')->findOrFail($id);
            return response()->json($user->points);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // Get the files uploaded by a user
    public function files($id)
    {
        try {
            $user = User::with('files')->findOrFail($id);
            return response()->json($user->files);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // Get the transactions of a user
    public function transactions($id)
    {
        try {
            $user = User::with('transactions')->findOrFail($id);
            return response()->json($user->transactions);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
}
