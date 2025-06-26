<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function showForgotPassword()
    {
        // Display form where user enters email to get reset link
    }

    
    public function sendResetLinkEmail(Request $request)
    {
        // Validate email and send reset link
    }

    
    public function showResetPasswordForm($token)
    {
        // Display form to reset password using the token
    }

    
    public function resetPassword(Request $request)
    {
        // Validate and update the new password
    }
}
