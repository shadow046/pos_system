@component('mail::message')
Hi {{$profile->first_name}},

A sign in attempt requires further verification because we did not recognize your device. To complete the sign in, enter the verification code on the unrecognized device.

Device: {{$device}}<br/>
Verification code: {{$user->otp}}

If you did not attempt to sign in to your account, your password may be compromised. Contact us at sample@email.com.

Thanks,

Your {{ config('app.name') }} Team
@endcomponent