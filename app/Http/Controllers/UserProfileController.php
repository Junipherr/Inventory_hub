<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $profiles = User::all();
        return view('profile.edit', ['profiles' => $profiles]);
    }

    /**
     * Handle profile registration.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'room_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Find or create the room by name
        $room = \App\Models\Room::firstOrCreate(['name' => $validated['room_name']]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(str_replace(' ', '_', $validated['name'])) . '@example.com',
            'password' => Hash::make($validated['password']),
            'role' => 'Viewer',
            'custodian_id' => auth()->id(),
            'room_id' => $room->id,
        ]);

        return Redirect::route('profile.index')->with('status', 'Profile registered successfully.');
    }

    /**
     * Display the viewer dashboard with user-specific data.
     */
    public function viewerDashboard()
    {
        // Fetch all items with their units without filtering by custodian_id or room_id
        $items = \App\Models\Item::with('units')->get();

        return view('viewer.dashboard', compact('items'));
    }
}
