<?php

namespace App\DTO;

class TokenResponse
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $plainTextToken,
        public $accessToken
        )
    {
        //
    }
}
