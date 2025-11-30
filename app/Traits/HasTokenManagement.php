<?php

namespace App\Traits;

trait HasTokenManagement
{
    /**
     * 获取当前有效的 API token
     */
    public function getValidToken(string $tokenName = 'auth_token')
    {
        return $this->tokens()
            ->where('name', $tokenName)
            ->where('expires_at', '>', now())
            ->latest('expires_at')
            ->first();
    }

    /**
     * 清除所有过期的 tokens
     */
    public function clearExpiredTokens(): void
    {
        $this->tokens()
            ->where('expires_at', '<=', now())
            ->delete();
    }


    /**
     * 创建或获取有效的 API token
     */
    public function createTokenWithExpiry(string $name = 'auth_token', array $abilities = ['*'], int $expireInDays = 7)
    {
        // 先清理过期的 tokens
        $this->clearExpiredTokens();
        
       // 检查是否已有有效的 token
        if ($existingToken = $this->getValidToken($name)) {
            return new \App\DTO\TokenResponse(
                $existingToken->token,
                $existingToken
            );
        }

        // 创建新 token
        return $this->createToken($name, $abilities, now()->addDays($expireInDays));
    }

}
