<?php

namespace App\Rules;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckOtpValidityRule implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(protected User $user)
    {
        // 
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->notValidOtp($value))
        {
            $fail('Verification code is not valid.');
        }

        if($this->otpExpired($value))
        {
            $fail('The verification code has expired. Please send again.');
        }
    }

    // Check otp is not valid.
    protected function notValidOtp($otp): bool
    {
        return $this->user->otp != $otp;
    }

    // Check otp was expired.
    protected function otpExpired($otp): bool
    {
        $expired_date = Carbon::parse($this->user->otp_expired_at);

        return $this->user->otp == $otp && $expired_date->lt(now());
    }
}
