<?php

namespace App\Contracts\Auth;

interface AuthServiceInterface
{
    public function loginWithFirebase(string $token): array;
    
    public function register(array $data): array;
}
