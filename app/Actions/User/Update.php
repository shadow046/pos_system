<?php

namespace App\Actions\User;

use App\Actions\Traits\AsObject;
use App\Http\Requests\Update\UserRequest;
use App\Models\User;

class Update
{
    use AsObject;

    /**
     * Create user.
     *
     * @param  User  $request
     */
    public function handle(User $user, UserRequest $request): void
    {
        $user->update([
            'email' => $request->email,
            'status' => $request->status,
        ]);

        $user->profile->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
        ]);

        $user->syncRoles([$request->role]);
    }
}
