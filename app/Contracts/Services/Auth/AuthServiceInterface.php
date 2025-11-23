<?php

namespace App\Contracts\Services\Auth;

use App\Models\User;
use Illuminate\Validation\ValidationException;

interface AuthServiceInterface
{
      /**
     * 使用邮箱和密码进行用户认证
     *
     * @param array $credentials 登录凭证
     * @return array 包含访问令牌和用户信息
     * @throws ValidationException 当认证失败时抛出
     * 
     * 返回示例:
     * [
     *     'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1Ni...',
     *     'token_type' => 'Bearer',
     *     'expires_in' => 3600,
     *     'user' => [...]
     * ]
     */
    public function loginWithCredentials(array $credentials): array;


    /**
     * Register a new user
    *
     * @param array $data 用户注册数据
     * @return array 新创建的用户实例 ,token
     * @throws ValidationException 当数据验证失败时抛出
     */
    public function register(array $data): array;

    /**
     * 用户登出
     * 
     * @param User $user 当前认证用户
     * @return bool 是否成功登出
     */
    public function logout(User $user): bool;

    /**
     * 刷新访问令牌
     * @param string $refreshToken 刷新令牌
     * @return array 新的访问令牌信息
     */
    public function refreshToken(string $refreshToken): array;

    /**
     * 获取当前认证用户
     *
     * @return User|null 当前认证用户或null
     */
    public function getAuthenticatedUser(): ?User;

    /**
     *  发送密码重置链接
     *
     * @param string $email 用户邮箱
     * @return array
     */
    public function requestPasswordReset(string $email): array;

    /**
     * Reset user's password
     *
     * @param array $credentials 包含email, token, password等
     * @return bool 是否重置成功
     */
    public function resetPassword(array $credentials): bool;

 

 
}