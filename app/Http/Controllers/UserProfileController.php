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
            'email' => 'nullable|string|email|max:255|unique:users',
            'room_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Full name is required.',
            'name.string' => 'Name must be a valid text.',
            'name.max' => 'Name must not exceed 255 characters.',
            'email.string' => 'Email must be a valid text.',
            'email.email' => 'Please enter a valid email address format.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'This email address is already registered. Please use a different email address.',
            'room_name.required' => 'Room name is required.',
            'room_name.string' => 'Room name must be a valid text.',
            'room_name.max' => 'Room name must not exceed 255 characters.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid text.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match the password.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'error_type' => 'validation'
            ], 422);
        }

        try {
            // Create or find room
            $room = Room::firstOrCreate(['name' => $request->room_name]);

            // Auto-generate email if not provided
            $email = $request->email;
            if (!$email) {
                // Generate email based on name and unique identifier
                $baseName = strtolower(preg_replace('/[^a-z0-9]/', '', str_replace(' ', '', $request->name)));
                $uniqueId = uniqid() . rand(1000, 9999);
                $email = $baseName . '.' . $uniqueId . '@inventory.local';
                
                // Ensure uniqueness with a more robust approach
                $counter = 1;
                $originalEmail = $email;
                while (User::where('email', $email)->exists()) {
                    $email = $baseName . '.' . $uniqueId . '_' . $counter . '@inventory.local';
                    $counter++;
                    if ($counter > 100) { // Prevent infinite loops
                        $email = 'user.' . uniqid() . '@inventory.local';
                        break;
                    }
                }
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $email,
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
