<?php

namespace App\Contracts\Auth;

interface FirebaseAuthServiceInterface
{
     public function verifyToken(string $token): array;
}
