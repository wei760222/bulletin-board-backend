<?php

namespace App\Contracts\Services\Verification;

interface VerificationServiceInterface
{
    /**
     * 发送验证码
     * @param string $target 手机号/邮箱
     * @param string $type 验证类型 (sms/email)
     */
    public function sendVerificationCode(string $target, string $type): void;

    /**
     * 验证验证码
     * @param string $target 手机号/邮箱
     * @param string $code 验证码
     * @param string $type 验证类型 (sms/email)
     */
    public function verifyCode(string $target, string $code, string $type): bool;
}
