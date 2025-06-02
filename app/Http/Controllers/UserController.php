<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // Debug the incoming request
        // \Log::debug('User creation attempt', $request->all());

        $validated = $request->validated();

        try {
            User::create($validated);

            return redirect()->route('users.create')
                ->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            // \Log::error('User creation failed: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
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
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {

            // Handle password hashing in the model (via mutator)
            $user->update($request->validated());

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            // Log::error("User update failed: {$e->getMessage()}");
            return back()->with('error', 'Update failed. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Deletion failed: ' . $e->getMessage());
        }

    }

}