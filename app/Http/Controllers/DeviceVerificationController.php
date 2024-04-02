<?php

namespace App\Http\Controllers;

use App\Actions\GetIpAddress;
use App\Http\Requests\VerifyDeviceRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DeviceVerificationController extends Controller
{
    /**
     * Device verification.
     */
    public function show(): Response
    {
        return Inertia::render('Auth/VerifyDevice', [
            'email' => auth()->user()->maskedEmail,
            'expired_at' => date('h:iA', strtotime(auth()->user()->otp_expired_at)),
        ]);
    }

    /**
     * Verify device
     */
    public function store(VerifyDeviceRequest $request): RedirectResponse
    {
        auth()->user()->update([
            'otp' => null,
            'otp_expired_at' => null,
            'device_verified_at' => now()->format('Y-m-d H:i:s'),
            'current_ip_address' => GetIpAddress::run(),
        ]);

        return redirect()->route('dashboard');
    }
}
