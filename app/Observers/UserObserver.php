<?php

namespace App\Observers;

use App\Actions\GenerateActivityLog;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        GenerateActivityLog::run('Created', $user, "Added new user account for {$user->email}");
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if (! $user->wasChanged('remember_token'))
        {
            GenerateActivityLog::run('Updated', $user, "Edited {$user->email} account credentials");
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
