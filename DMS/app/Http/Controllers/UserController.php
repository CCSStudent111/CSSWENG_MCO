<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('department')->get();

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();

        return Inertia::render('Users/Create', [
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
            'is_admin' => 'required|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => $user->load('department'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
         $departments = Department::all();

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'departments' => $departments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only admins can delete users.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }


    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();

        return redirect()->back()->with('success', 'Admin status updated.');
    }

    public function toggleManager(User $user)
    {
        $user->role = $user->role === 'manager' ? null : 'manager';
        $user->save();

        return redirect()->back()->with('success', 'Manager status updated.');
    }

    public function trashed()
    {
        $trashedUsers = User::onlyTrashed()->with('department')->get();
        
        return Inertia::render('Users/Trashed', [
            'users' => $trashedUsers
        ]);
    }
}
