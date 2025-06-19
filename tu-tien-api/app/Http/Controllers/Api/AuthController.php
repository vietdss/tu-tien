<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Đăng ký
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Đăng ký thành công',
            'data'    => [
                'user' => new UserResource($user),
            ],
        ], 201); 
    }

    /**
     * Đăng nhập
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials['email'], $credentials['password']);

        return response()->json([
            'status'  => true,
            'message' => 'Đăng nhập thành công',
            'data'    => [
                'access_token' => $result['token'],
                'token_type'   => 'Bearer',
                'user'         => new UserResource($result['user']),
            ],
        ], 200);
    }

    /**
     * Đăng xuất
     */
    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Đăng xuất thành công',
        ], 200);
    }
}
