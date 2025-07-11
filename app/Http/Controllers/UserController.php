<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view users')->only(['index', 'show']);
        $this->middleware('can:create users')->only(['create', 'store']);
        $this->middleware('can:update users')->only(['edit', 'update']);
        $this->middleware('can:delete users')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $roles = $request->input('roles', []);
        unset($validated['roles']);
        $user = User::create($validated);
        if (!empty($roles)) {
            $user->assignRole($roles);
        }
        return redirect()->route('admin.users.create')
            ->with('success', 'User created successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $roles = $request->input('roles', []);
        unset($validated['roles']);
        $user->update($validated);
        $user->syncRoles($roles);
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Deletion failed: ' . $e->getMessage());
        }

    }


}
