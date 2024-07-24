<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(new BaseResource('error', null, 'Unauthorized', ['Invalid credentials']), 401);
        }

        $user = Auth::user();

        $responseData = [
            'user' => new UserResource($user),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ];

        return response()->json(new BaseResource('success', (object) $responseData, 'Login successful'), 200);
    }

    public function register(Request $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
        ];

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|min:9',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(new BaseResource('error', null, 'Validation Failed', $validator->errors()->all()), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'phone' => $request->phone,
        ]);

        return response()->json(new BaseResource('success', new UserResource($user), 'User registered successfully'), 201);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(new BaseResource('success', null, 'Successfully logged out'), 200);
    }

    public function refresh()
    {
        $responseData = [
            'user' => new UserResource(Auth::user()),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
        ];

        return response()->json(new BaseResource('success', (object) $responseData, 'Token refreshed'), 200);
    }
}
