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

/**
 * @OA\Tag(
 *     name="認證",
 *     description="認證相關的 API"
 * )
 */
class AuthController extends BaseController
{
    use ApiResponse;
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="loginUser",
     *      tags={"認證"},
     *      summary="用戶登入",
     *      description="使用 Firebase Token 進行登入",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"firebase_token"},
     *              @OA\Property(property="firebase_token", type="string", example="your-firebase-id-token")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="登入成功",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Login successful"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="access_token", type="string", example="1|randomtoken123"),
     *                  @OA\Property(property="token_type", type="string", example="Bearer"),
     *                  @OA\Property(property="user", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="John Doe"),
     *                      @OA\Property(property="email", type="string", example="john@example.com"),
     *                      @OA\Property(property="role", type="string", example="user")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=401, description="認證失敗"),
     *      @OA\Response(response=422, description="驗證錯誤")
     * )
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
     * @OA\Post(
     *     path="/api/register",
     *     operationId="registerUser",
     *     tags={"認證"},
     *     summary="用戶註冊",
     *     description="註冊新用戶",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="註冊成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com")
     *                 ),
     *                 @OA\Property(property="access_token", type="string", example="your_access_token"),
     *                 @OA\Property(property="token_type", type="string", example="Bearer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="驗證錯誤",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="The email has already been taken.")
     *                 )
     *             )
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/user",
     *     operationId="getUserProfile",
     *     tags={"認證"},
     *     summary="取得當前用戶資料",
     *     description="取得當前登入用戶的資料",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="成功取得用戶資料",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="未授權",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="未授權")
     *         )
     *     )
     * )
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
