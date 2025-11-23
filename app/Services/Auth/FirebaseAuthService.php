<?php

namespace App\Services\Auth;

use App\Contracts\Services\Auth\FirebaseAuthServiceInterface;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseAuthService implements FirebaseAuthServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function verifyToken(string $token): array
    {
        try {
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($token);
             return [
                'success' => true,
                'uid' => $verifiedIdToken->claims()->get('sub'),
                'name' => $verifiedIdToken->claims()->get('name', 'User'),
                'email' => $verifiedIdToken->claims()->get('email')
            ];
        } catch (FailedToVerifyToken $e) {
             return [
                'success' => false,
                'message' => 'Invalid token'
            ];
        }
    }

    public function loginWithFirebase(string $token): array
    {
        $verification = $this->verifyToken($token);
        
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
}
