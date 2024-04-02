<?php

namespace App\Actions\User;

use App\Actions\Traits\AsObject;
use App\Events\NewUserEvent;
use App\Http\Requests\Store\UserRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Create
{
    use AsObject;

    /**
     * Create user.
     */
    public function handle(UserRequest $request): void
    {
        $password = Str::random(8);

        $user = $this->createUser($request, $password);

        $this->createProfile($request, $user);

        $user->assignRole($request->role);

        event(new NewUserEvent($user, $password));
    }

    /**
     * Create user.
     */
    protected function createUser(UserRequest $request, string $password): User
    {
        return User::create([
            'email' => $request->email,
            'password' => Hash::make($password),
            'status' => $request->status,
        ]);
    }

    /**
     * Create profile.
     */
    protected function createProfile(UserRequest $request, User $user): void
    {
        Profile::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
        ]);
    }
}
