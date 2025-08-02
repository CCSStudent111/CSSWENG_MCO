<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display the user's profile page with current user data
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Load the department relationship
        $user->load('department');

        return Inertia::render('Profile/Index', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the user's profile
     */
    public function edit()
    {
        return Inertia::render('Profile/Edit', [
            'user' => Auth::user()->load('department'),
        ]);
    }

    /**
     * Update the user's profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_num',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
        ], [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'username.alpha_num' => 'Username may only contain letters and numbers.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already taken.',
            'email.email' => 'Please enter a valid email address.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
        ]);

        // Use ProfileService to update
        $this->profileService->update($user, $validated);

        return redirect()->route('profile.index')
            ->with('message', 'Profile updated successfully!');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                ],
            ], [
                'current_password.required' => 'Please enter your current password.',
                'current_password.current_password' => 'The current password is incorrect.',
                'password.required' => 'Please enter a new password.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.regex' => 'Password must contain uppercase, lowercase, number and special character.',
            ]);

            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->route('profile.index')
                ->with('message', 'Password updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Password update error: ' . $e->getMessage());
            
            return redirect()->route('profile.index')
                ->with('error', 'Failed to update password. Please try again.');
        }
    }

    /**
     * Get current user data as JSON (for API calls)
     */
    public function getCurrentUser()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user->load('department');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'suffix' => $user->suffix,
                'date_of_birth' => $user->date_of_birth,
                'full_name' => $this->getFullName($user),
                'role' => $this->getUserRole($user),
                'is_admin' => $user->is_admin,
                'is_manager' => $user->is_manager ?? false,
                'department' => $user->department,
                'department_id' => $user->department_id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'email_verified_at' => $user->email_verified_at,
            ]
        ]);
    }

    /**
     * Get user's activity history
     */
    public function getActivity()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $activities = collect();

        // Documents created by user
        if ($user->documents()) {
            $documentsCreated = $user->documents()
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($document) {
                    return [
                        'type' => 'document_created',
                        'description' => "Created document: {$document->title}",
                        'date' => $document->created_at,
                        'data' => $document->only(['id', 'title', 'status'])
                    ];
                });
            
            $activities = $activities->merge($documentsCreated);
        }

        // Documents approved by user (if manager/admin)
        if (($user->is_manager || $user->is_admin) && method_exists($user, 'approvedDocuments')) {
            $documentsApproved = $user->approvedDocuments()
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($document) {
                    return [
                        'type' => 'document_approved',
                        'description' => "Approved document: {$document->title}",
                        'date' => $document->updated_at,
                        'data' => $document->only(['id', 'title', 'status'])
                    ];
                });

            $activities = $activities->merge($documentsApproved);
        }

        $activities = $activities->sortByDesc('date')
            ->take(20)
            ->values();

        return response()->json([
            'activities' => $activities
        ]);
    }

    /**
     * Get user's statistics
     */
    public function getStats()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $stats = [
            'account_age_days' => now()->diffInDays($user->created_at),
        ];

        // Document statistics if user has documents relationship
        if ($user->documents()) {
            $stats['documents_created'] = $user->documents()->count();
            $stats['pending_documents'] = $user->documents()->where('status', 'pending')->count();
            $stats['approved_documents'] = $user->documents()->where('status', 'approved')->count();
            $stats['rejected_documents'] = $user->documents()->where('status', 'rejected')->count();
        }

        // Additional stats for managers/admins
        if ($user->is_manager || $user->is_admin) {
            if (method_exists($user, 'approvedDocuments')) {
                $stats['documents_approved'] = $user->approvedDocuments()->count();
            }
            
            // Check if Document model exists
            if (class_exists('\App\Models\Document')) {
                $stats['pending_reviews'] = \App\Models\Document::where('status', 'pending')->count();
            }
        }

        return response()->json([
            'stats' => $stats
        ]);
    }

    /**
     * Show user's security settings
     */
    public function security()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $securityInfo = [
            'last_login' => $user->updated_at, // Approximation using last update
            'password_changed' => $user->updated_at, // Approximation
            'account_created' => $user->created_at,
            'email_verified' => $user->email_verified_at !== null,
        ];

        return Inertia::render('Profile/Security', [
            'user' => $user,
            'security_info' => $securityInfo
        ]);
    }

    /**
     * Verify current password (for sensitive operations)
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        return response()->json(['verified' => true]);
    }

    /**
     * Get user preferences/settings
     */
    public function getPreferences()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Since we're not adding new columns, we'll use existing data or defaults
        $preferences = [
            'theme' => 'light', // Default theme
            'notifications' => [
                'email' => true,
                'browser' => true,
                'document_updates' => true,
                'system_alerts' => $user->is_admin || $user->is_manager,
            ],
            'language' => 'en',
            'timezone' => 'UTC',
            'items_per_page' => 10,
        ];

        return response()->json([
            'preferences' => $preferences
        ]);
    }

    /**
     * Update user preferences (store in session or cache since no new columns)
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'in:light,dark',
            'notifications' => 'array',
            'language' => 'string|max:5',
            'timezone' => 'string|max:50',
            'items_per_page' => 'integer|min:5|max:100',
        ]);

        // Store preferences in session since we're not adding database columns
        session(['user_preferences' => $validated]);

        return response()->json([
            'message' => 'Preferences updated successfully',
            'preferences' => $validated
        ]);
    }

    /**
     * Helper method to get full name
     */
    private function getFullName($user)
    {
        $parts = array_filter([
            $user->first_name,
            $user->middle_name,
            $user->last_name,
            $user->suffix
        ]);
        
        return implode(' ', $parts) ?: $user->username;
    }

    /**
     * Helper method to get user role
     */
    private function getUserRole($user)
    {
        if ($user->is_admin) {
            return 'Administrator';
        } elseif ($user->is_manager ?? false) {
            return 'Manager';
        } else {
            return 'Employee';
        }
    }
}