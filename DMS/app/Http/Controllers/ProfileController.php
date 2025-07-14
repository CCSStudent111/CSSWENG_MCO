<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Inertia\Inertia;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function edit()
    {
        return Inertia::render('Profile/Edit', [
            'user' => Auth::user()->load('department'),
        ]);
    }

    public function update(UpdateUserRequest $request)
    {
        $this->profileService->update(Auth::user(), $request->validated());

        return redirect()->back()->with('success', 'Profile updated.');
    }
}