<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\RoleRequest;
use App\Http\Requests\Update\RoleRequest as UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    /**
     * Display roles.
     */
    public function index(): Response
    {
        return Inertia::render('Roles/Index', [
            'roles' => Role::latest('id')->simplePaginate(10),
        ]);
    }

    /**
     * Create role.
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        Role::create($request->only('name'));

        return redirect()->intended(route('roles.index'));
    }

    /**
     * Update role.
     */
    public function update(Role $role, UpdateRoleRequest $request): RedirectResponse
    {
        $role->update($request->only('name'));

        return redirect()->intended(route('roles.index'));
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json([
            'message' => 'Role has been removed.',
        ], 201);
    }
}
