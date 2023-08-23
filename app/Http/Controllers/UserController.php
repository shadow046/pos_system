<?php

namespace App\Http\Controllers;

use App\Actions\User\Create;
use App\Actions\User\Update;
use App\Events\NewUserEvent;
use App\Http\Requests\Store\UserRequest;
use App\Http\Requests\Update\UserRequest as UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display users.
     * 
     * @return Response
     */
    public function index() : Response
    {
        return Inertia::render('Users/Index', [
            'users' => User::with('profile', 'roles')->latest('id')->simplePaginate(10),
            'roles' => Role::pluck('name')
        ]);
    }

    /**
     * Create user.
     * 
     * @param UserRequest $request
     * @return RedirectResponse
     */
    public function store(UserRequest $request) : RedirectResponse
    {
        Create::run($request);

        return redirect()->intended(route('users.index'));
    }

    /**
     * Update user.
     * 
     * @param User $user
     * @param UpdateUserRequest $request
     * @return RedirectResponse
     */
    public function update(User $user, UpdateUserRequest $request) : RedirectResponse
    {
        Update::run($user, $request);

        return redirect()->intended(route('users.index'));
    }

    /**
     * Resend password credentials.
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function resend(User $user) : JsonResponse
    {
        $password = Str::random(8);

        $user->update([
            'password' => Hash::make($password),
            'has_changed_password' => false,
        ]);

        event(new NewUserEvent($user, $password));

        return response()->json([
            'message' => 'Password has been resent!'
        ], 201);
    }
}
