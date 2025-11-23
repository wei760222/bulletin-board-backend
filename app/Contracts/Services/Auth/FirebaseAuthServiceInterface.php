<?php

namespace App\Contracts\Services\Auth;

interface FirebaseAuthServiceInterface
{
     public function verifyToken(string $token): array;

      /**
     * Authenticate a user using Firebase token
     *
     * @param string $token
     * @return array
     * @throws ValidationException
     */
    public function loginWithFirebase(string $token): array;
}
