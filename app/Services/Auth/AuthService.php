<?php

namespace App\Services\Auth;

use App\Contracts\Services\Auth\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Str;

class AuthService implements AuthServiceInterface
{
   
    public function __construct()
    {
        // 可以留空或注入 Logger 等辅助服务
    }

  
    /**
     * 用户登录
     *
     * @param array{
     *     email: string,
     *     password: string,
     *     recaptcha_token?: string,
     *     otp?: string,
     *     device_info?: array
     * } $credentials 登录凭证
     * 
     * @return array{
     *     user: \App\Models\User,
     *     token: string,
     *     token_type: string
     * }
     * 
     * @throws \Illuminate\Validation\ValidationException
     */

      public function loginWithCredentials(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'user'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'success' => true,
            'user' => $user,
            'token' => $token
        ];
    }


    
    public function logout(User $user): bool
    {
        $user->currentAccessToken()->delete();
        return true;
    }

    public function refreshToken(string $refreshToken): array
    {
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function getAuthenticatedUser(): User
    {
        return Auth::user();
    }

    public function requestPasswordReset(string $email): array
    {
        $status = Password::sendResetLink(['email' => $email]);

        return [
            'status' => $status === Password::RESET_LINK_SENT,
            'message' => __($status)
        ];
    }

    public function resetPassword(array $credentials): bool
    {
        $status = Password::reset(
            $credentials,
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET;
    }
    

}
