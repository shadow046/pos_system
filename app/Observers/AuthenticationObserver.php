<?php

namespace App\Observers;

use App\Actions\GenerateActivityLog;
use App\Models\User;
use hisorange\BrowserDetect\Parser as Browser;

class AuthenticationObserver
{
    // Login user
    public function login(User $user): void
    {
        GenerateActivityLog::run('Login', $user, 'Login using '.Browser::browserFamily().' on '.Browser::platformFamily());
    }

    // Logout user
    public function logout(User $user): void
    {
        GenerateActivityLog::run('Logout', $user, 'Logout from '.Browser::browserFamily().' on '.Browser::platformFamily());
    }
}
