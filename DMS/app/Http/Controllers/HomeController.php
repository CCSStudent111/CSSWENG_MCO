<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Document;
use App\Models\Client;
use App\Models\User;
use App\Models\Department;

class HomeController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard', [
            'documents' => \App\Models\Document::all(),
            'clients' => \App\Models\Client::all(),
            'users' => \App\Models\User::all(),
             'departments' => Department::all(),
        ]); 
    }
}
