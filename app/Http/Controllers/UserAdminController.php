<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserAdminController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::orderBy('name', 'asc')->get(),
            'roles' => User::$availableRoles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.register', [
            'user' => new User(),
            'roles' => User::$availableRoles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRegistrationRequest $request)
    {
        // Create the user, then assign the roles
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        foreach(User::$availableRoles as $role) {
            if ($request->validated($role)) {
                $user->assignRole($role);
            }
        }

        return view('status.info',
            [
                'message' => "User created: $user->name ",
            ]
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Same content as edit(), but clientside jscript will lock the form fields
        return view('users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => User::$availableRoles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserAdminRequest $request, User $user)
    {
        $user->fill($request->validated());

        foreach(User::$availableRoles as $role) {
            if ($request->validated($role) && !$user->hasRole($role)) {
                // assigned the role and user does not have it yet
                $user->assignRole($role);
            } elseif ($user->hasRole($role)) {
                // revoked the role but user currently has it
                $user->revokeRole($role);
            }
        }

        return view('status.info',
            [
                'message' => "User updated: $user->name",
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        // must remove all assigned roles first
        foreach(User::$availableRoles as $role) {
            if ($user->hasRole($role)) {
                $user->revokeRole($role);
            }
        }

        $user->delete();

        return view('status.warning',
            [
                'message' => "User deleted: $user->name",
            ]
        );
    }
}
