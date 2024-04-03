<?php

namespace App\Actions;

use App\Actions\Traits\AsObject;
use App\Events\VerifyDeviceEvent;
use App\Models\User;
use hisorange\BrowserDetect\Parser as Browser;

class SendVerificationCode
{
    use AsObject;

    /**
     * Send verification code.
     */
    public function handle(User $user): void
    {
        $user->update([
            'otp' => mt_rand(100000, 999999),
            'otp_expired_at' => now()->addHour(1)->format('Y-m-d H:i:s'),
            'device_verified_at' => null,
        ]);

        event(new VerifyDeviceEvent($user, Browser::browserFamily().' on '.Browser::platformFamily()));
    }
}
