<?php

namespace App\Services\Auth;

use App\Contracts\Auth\AuthServiceInterface;
use App\Contracts\Auth\FirebaseAuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Str;

class AuthService implements AuthServiceInterface
{
    protected FirebaseAuthServiceInterface $firebaseAuthService;

    public function __construct(FirebaseAuthServiceInterface $firebaseAuthService)
    {
        $this->firebaseAuthService = $firebaseAuthService;
    }

     public function loginWithFirebase(string $token): array
    {
        $verification = $this->firebaseAuthService->verifyToken($token);
        
        if (!$verification['success']) {
            return [
                'success' => false,
                'message' => $verification['message'] ?? 'Authentication failed'
            ];
        }

        $user = User::firstOrCreate(
            ['firebase_uid' => $verification['uid']],
            [
                'name' => $verification['name'],
                'email' => $verification['email'],
                'password' => bcrypt(Str::random(16)),
                'role' => 'user'
            ]
        );

        return [
            'success' => true,
            'user' => $user
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

    

}
