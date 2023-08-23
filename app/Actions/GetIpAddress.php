<?php

namespace App\Actions;

use App\Actions\Traits\AsObject;

class GetIpAddress
{
    use AsObject;

    /**
     * Get ip address
     * 
     * @return string
     */
    public function handle(): string
    {
        return (isset($_SERVER['HTTP_CF_CONNECTING_IP']))
            ? $_SERVER['HTTP_CF_CONNECTING_IP']
            : request()->ip();
    }
}
