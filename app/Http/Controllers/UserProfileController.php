<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Display all user profiles
     */
    public function index()
    {
        try {
            // Load all user profiles with their rooms
            $profiles = User::with('room')->get();
            
            if ($profiles->isEmpty()) {
                \Log::info('No profiles found in UserProfileController@index');
            }
            
            return view('profile.users', compact('profiles'));
        } catch (\Exception $e) {
            \Log::error('Error loading profiles: ' . $e->getMessage());
            return view('profile.users', ['profiles' => collect([])]);
        }
    }

    /**
     * Store a newly created user profile
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'room_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'This email address is already registered. Please use a different email.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create or find room
            $room = Room::firstOrCreate(['name' => $request->room_name]);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'room_id' => $room->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully!',
                'user' => $user->load('room')
            ]);

        } catch (\Exception $e) {
            // Handle constraint violations
            if (strpos($e->getMessage(), 'users_email_unique') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email address is already registered.',
                    'errors' => ['email' => ['This email address is already registered.']]
                ], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'errors' => ['general' => [$e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Display the specified profile information
     */
    public function show($id)
    {
        try {
            $user = User::with(['room'])->findOrFail($id);
            
            // Get password information
            $passwordUpdatedAt = $user->password_updated_at;
            $hasPassword = !empty($user->password);
            
            $daysSinceUpdate = null;
            if ($passwordUpdatedAt) {
                $daysSinceUpdate = now()->diffInDays($passwordUpdatedAt);
            }
            
            return response()->json([
                'success' => true,
                'profile' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'room' => $user->room,
                    'created_at' => $user->created_at->format('M d, Y'),
                    'password_info' => [
                        'has_password' => $hasPassword,
                        'last_updated' => $passwordUpdatedAt ? $passwordUpdatedAt->format('M d, Y') : 'Never',
                        'days_since_update' => $daysSinceUpdate
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile information'
            ], 500);
        }
    }

    /**
     * Remove the specified profile
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete profile. Please try again.'
            ], 500);
        }
    }
}
