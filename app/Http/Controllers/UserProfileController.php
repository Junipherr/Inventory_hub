<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class UserProfileController extends Controller
{
    /**
     * Display the list of profiles.
     */
    public function index()
    {
        $profiles = User::with('room')->get();
        $rooms = Room::all();
        $user = auth()->user();
        return view('profile.edit', ['profiles' => $profiles, 'rooms' => $rooms, 'user' => $user]);
    }

    /**
     * Get the details of a specific profile.
     */
    public function show($id)
    {
        try {
            $profile = User::with('room')->findOrFail($id);
            
            // Only allow admins to see other profiles
            if (auth()->user()->id !== $profile->id && !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view this profile'
                ], 403);
            }
            
            // Calculate days since last password update
            $daysSincePasswordUpdate = null;
            if ($profile->password_updated_at) {
                $daysSincePasswordUpdate = $profile->password_updated_at->diffInDays(now());
            }
            
            return response()->json([
                'success' => true,
                'profile' => [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'email' => $profile->email,
                    'role' => $profile->role,
                    'room' => $profile->room ? [
                        'id' => $profile->room->id,
                        'name' => $profile->room->name
                    ] : null,
                    'password_info' => [
                        'has_password' => !empty($profile->password),
                        'last_updated' => $profile->password_updated_at ? $profile->password_updated_at->format('Y-m-d H:i:s') : 'Never',
                        'days_since_update' => $daysSincePasswordUpdate,
                    ],
                    'created_at' => $profile->created_at,
                    'updated_at' => $profile->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile information'
            ], 404);
        }
    }

    /**
     * Handle profile registration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'room_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Find or create the room by name
        $room = Room::firstOrCreate(['name' => $validated['room_name']]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(str_replace(' ', '_', $validated['name'])) . '@example.com',
            'password' => Hash::make($validated['password']),
            'role' => 'Viewer',
            'custodian_id' => auth()->id(),
            'room_id' => $room->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Profile registered successfully.']);
        }

        return Redirect::route('profile.index')->with('status', 'Profile registered successfully.');
    }

    /**
     * Show the form for editing the specified profile.
     */
    public function edit($id)
    {
        $profile = User::findOrFail($id);
        $rooms = Room::all();
        return view('profile.edit-single', compact('profile', 'rooms'));
    }

    /**
     * Update the specified profile.
     */
    public function update(Request $request, $id)
    {
        $profile = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $profile->name = $validated['name'];
        $profile->email = $validated['email'];
        $profile->room_id = $validated['room_id'];
        
        if (!empty($validated['password'])) {
            $profile->updatePassword($validated['password']);
        }
        
        $profile->save();

        return response()->json([
            'success' => true, 
            'message' => 'Profile updated successfully.',
            'profile' => [
                'id' => $profile->id,
                'name' => $profile->name,
                'email' => $profile->email,
                'has_password' => !empty($profile->password),
                'password_updated_at' => $profile->password_updated_at ? $profile->password_updated_at->format('Y-m-d H:i:s') : null,
            ]
        ]);
    }

    /**
     * Remove the specified profile.
     */
    public function destroy($id)
    {
        $profile = User::findOrFail($id);
        
        // Prevent deletion of the currently authenticated user
        if ($profile->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own profile.'], 403);
        }
        
        $profile->delete();
        
        return response()->json(['success' => true, 'message' => 'Profile deleted successfully.']);
    }

    /**
     * Display the viewer dashboard with user-specific data.
     */
    public function viewerDashboard()
    {
        $user = auth()->user();

        if ($user->role === 'Admin') {
            // Admin can see all rooms
            $rooms = \App\Models\Room::with(['items.units'])->get();
        } else {
            // Viewer can see only their assigned room
            $rooms = \App\Models\Room::with(['items.units'])
                ->where('id', $user->room_id)
                ->get();
        }

        return view('viewer.dashboard', compact('rooms'));
    }
}
