<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Handles token-based authentication via Laravel Sanctum.
 */
class AuthController extends Controller
{
    /**
     * Register a new user and return a bearer token.
     *
     * @param  Request $request
     * @return JsonResponse         201 with token and user data
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Account created successfully.',
            'token'   => $token,
            'user'    => $user->only('id', 'name', 'email'),
        ], 201);
    }

    /**
     * Authenticate a user and return a fresh bearer token.
     *
     * @param  Request $request
     * @return JsonResponse
     * @throws ValidationException When credentials are incorrect
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Authenticated.',
            'token'   => $token,
            'user'    => $user->only('id', 'name', 'email'),
        ]);
    }

    /**
     * Revoke the current access token (logout).
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    /**
     * Return the currently authenticated user.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
