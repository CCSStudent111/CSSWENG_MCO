<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Department;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister()
    {
        $departments = Department::all();
        return Inertia::render("Auth/Register", [
            'departments' => $departments,
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'date_of_birth' => ['required', 'date'],
            'department_id' => ['required', 'exists:departments,id'],
            'is_admin' => ['required', 'boolean'],
            'is_manager' => ['required', 'boolean'],
        ]);

        User::create($validated);

        return redirect(route('login'));
    }

    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        throw ValidationException::withMessages([
            'message' => 'The provided username or password is incorrect.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
