<?php

namespace App\Observers;

use App\Models\PersonalAccessToken;

class TokenObserver
{
    /**
     * Handle the PersonalAccessToken "created" event.
     */
    public function created(PersonalAccessToken $personalAccessToken): void
    {
        // 預設 7 天過期
        if (empty($token->expires_at)) {
            $token->expires_at = now()->addDays(7);
        }
    }

    /**
     * Handle the PersonalAccessToken "updated" event.
     */
    public function updated(PersonalAccessToken $personalAccessToken): void
    {
        //
    }

    /**
     * Handle the PersonalAccessToken "deleted" event.
     */
    public function deleted(PersonalAccessToken $personalAccessToken): void
    {
        //
    }

    /**
     * Handle the PersonalAccessToken "restored" event.
     */
    public function restored(PersonalAccessToken $personalAccessToken): void
    {
        //
    }

    /**
     * Handle the PersonalAccessToken "force deleted" event.
     */
    public function forceDeleted(PersonalAccessToken $personalAccessToken): void
    {
        //
    }

    public function retrieved(PersonalAccessToken $token)
    {
        // 自動清理過期 token
        if ($token->expires_at && $token->expires_at->isPast()) {
            $token->delete();
            return null;
        }
        return $token;
    }
}
