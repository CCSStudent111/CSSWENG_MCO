<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showRegister()
    {
        // Display user registration form
    }

    public function register(Request $request)
    {
        // Handle user registration submission
    }

    public function showLogin()
    {
        // Display user login form
    }
    public function login(Request $request)
    {
        // process uesr login request
    }
    public function logout(Request $request)
    {
        // log the user out of the application
    }
}
