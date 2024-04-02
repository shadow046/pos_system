<?php

namespace App\Actions;

use App\Actions\Traits\AsObject;

class DeviceIsNotVerified
{
    use AsObject;

    /**
     * Check if device is not verified
     */
    public function handle(): bool
    {
        return $this->isNewIpAddress() || $this->isDeviceNotVerified();
    }

    // Check if ip address is not the same on current ip.
    protected function isNewIpAddress(): bool
    {
        return GetIpAddress::run() != auth()->user()->current_ip_address;
    }

    // Check if device was not verified.
    protected function isDeviceNotVerified()
    {
        return GetIpAddress::run() == auth()->user()->current_ip_address && blank(auth()->user()->device_verified_at);
    }
}
