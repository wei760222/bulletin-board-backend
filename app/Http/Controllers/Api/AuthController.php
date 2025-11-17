<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use App\Contracts\Auth\AuthServiceInterface;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponse;
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

   /**
     * 使用 Firebase Token 登入
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
          
            $result = $this->authService->loginWithFirebase($request->firebase_token);

            if (!$result['success']) {
              return $this->error($result['message'], 401);
            }

            $token = $result['user']->createToken('auth_token')->plainTextToken;

            return $this->success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => new AuthResource($result['user'])
            ], 'Login successful');

        } catch (ValidationException $e) {
            return $this->error('Validation error', 422, $e->errors());
        } catch (\Exception $e) {
           return $this->error('Login failed', 500, $this->getDebugMessage($e));
        }
    }

     /**
     * 用戶註冊
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
       
            $result = $this->authService->register($validated);

             return $this->success([
                'user' => new AuthResource($result['user']),
                'access_token' => $result['token'],
                'token_type' => 'Bearer'
            ], 'User registered successfully', 201);

        } catch (ValidationException $e) {
            return $this->error('Validation error', 422, $e->errors());
        } catch (\Exception $e) {
             return $this->error('Registration failed', 500, $this->getDebugMessage($e));
        }
    }

     /**
     * 獲取當前登入用戶資訊
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        try {
             return $this->success(
                new AuthResource(auth()->user()),
                'User retrieved successfully'
            );
        } catch (\Exception $e) {
             return $this->error('Failed to fetch user', 500, $this->getDebugMessage($e));
        }
    }

     /**
     * 用戶登出
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->success(null, 'Successfully logged out');
        } catch (\Exception $e) {
             return $this->error('Logout failed', 500, $this->getDebugMessage($e));
        }
    }

    /**
     * Get debug message
     *
     * @param Exception $e
     * @return array|null
     */

     protected function getDebugMessage(Exception $e): ?array
    {
        return config('app.debug') ? [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ] : null;
    }




}
