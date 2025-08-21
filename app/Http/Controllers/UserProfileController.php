<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\ItemUnit;
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
        // Redirect viewer users to their dashboard instead of showing 403
        if (auth()->user()->role !== 'Admin') {
            return redirect()->route('viewer.dashboard');
        }

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

    /**
     * Display the viewer dashboard
     */
    public function viewerDashboard()
    {
        $user = auth()->user();
        
        // Fetch items for the user's assigned room
        $room = \App\Models\Room::with(['items.units'])->find($user->room_id);
        
        if (!$room) {
            $room = \App\Models\Room::with(['items.units'])->first();
        }
        
        $items = $room ? $room->items : collect([]);
        
        return view('viewer.dashboard', compact('items', 'room'));
    }

    /**
     * Update the status of item units for the viewer
     */
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|array',
            'status.*' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updatedCount = 0;
            $statuses = $request->input('status', []);

            foreach ($statuses as $unitId => $status) {
                $unit = \App\Models\ItemUnit::find($unitId);
                if ($unit) {
                    $unit->status = $status;
                    $unit->save();
                    $updatedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updatedCount} item(s).",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating item statuses: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item statuses. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the borrow item form for viewers
     */
    public function showBorrowForm()
    {
        $user = auth()->user();
        
        // Get all items with proper available quantity calculation
        $availableItems = \App\Models\Item::with(['room'])
            ->get()
            ->map(function ($item) {
                // Use the actual quantity field from items table
                $totalQuantity = $item->quantity ?? 0;
                
                // Calculate quantity that is already borrowed via approved requests
                $approvedBorrowQuantity = \App\Models\BorrowRequest::where('item_id', $item->id)
                    ->where('status', 'approved')
                    ->sum('quantity');
                
                // Calculate actual available quantity
                $availableQuantity = max(0, $totalQuantity - $approvedBorrowQuantity);
                
                // Add calculated properties to the item
                $item->available_quantity = $availableQuantity;
                $item->total_quantity = $totalQuantity;
                $item->is_available = $availableQuantity > 0;
                
                return $item;
            });

        return view('viewer.borrow', compact('availableItems'));
    }

    /**
     * Submit a borrow request
     */
    public function submitBorrowRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'purpose' => 'required|string|max:500',
            'return_date' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = auth()->user();
            $item = \App\Models\Item::findOrFail($request->item_id);
            
            // Calculate available quantity
            $totalQuantity = $item->quantity ?? 0;
            $approvedBorrowQuantity = \App\Models\BorrowRequest::where('item_id', $item->id)
                ->where('status', 'approved')
                ->sum('quantity');
            $availableQuantity = max(0, $totalQuantity - $approvedBorrowQuantity);
            
            // Validate requested quantity against available quantity
            if ($request->quantity > $availableQuantity) {
                return back()->with('error', 'Requested quantity exceeds available quantity. Only ' . $availableQuantity . ' available.')
                    ->withInput();
            }

            // Create borrow request
            $borrowRequest = \App\Models\BorrowRequest::create([
                'user_id' => $user->id,
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'purpose' => $request->purpose,
                'return_date' => $request->return_date,
                'status' => 'pending',
            ]);

            return redirect()->route('viewer.borrow.history')
                ->with('success', 'Borrow request submitted successfully!');

        } catch (\Exception $e) {
            \Log::error('Error submitting borrow request: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit borrow request. Please try again.');
        }
    }

    /**
     * Display borrow history for the viewer
     */
    public function borrowHistory()
    {
        $user = auth()->user();
        
        $borrowRequests = \App\Models\BorrowRequest::with(['item', 'item.room'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('viewer.borrow-history', compact('borrowRequests'));
    }

    /**
     * Display all borrow requests for admin
     */
    public function adminBorrowRequests()
    {
        $query = \App\Models\BorrowRequest::with(['user', 'item', 'item.room'])
            ->orderBy('created_at', 'desc');

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $borrowRequests = $query->paginate(20);
        
        $pendingCount = \App\Models\BorrowRequest::where('status', 'pending')->count();
        $approvedCount = \App\Models\BorrowRequest::where('status', 'approved')->count();

        return view('custodian.borrow-requests.index', compact('borrowRequests', 'pendingCount', 'approvedCount'));
    }

    /**
     * Display pending borrow requests for admin
     */
    public function adminPendingRequests()
    {
        $pendingRequests = \App\Models\BorrowRequest::with(['user', 'item'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('custodian.borrow-requests.pending', compact('pendingRequests'));
    }

    /**
     * Display individual borrow request details for admin
     */
    public function adminShowBorrowRequest($id)
    {
        $borrowRequest = \App\Models\BorrowRequest::with(['user', 'item', 'item.room', 'item.units'])
            ->findOrFail($id);

        return view('custodian.borrow-requests.show', compact('borrowRequest'));
    }

    /**
     * Approve a borrow request
     */
    public function adminApproveBorrowRequest($id)
    {
        try {
            $borrowRequest = \App\Models\BorrowRequest::findOrFail($id);
            
            if ($borrowRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ]);
            }

            $borrowRequest->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Borrow request approved successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error approving borrow request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve request. Please try again.'
            ], 500);
        }
    }

    /**
     * Reject a borrow request
     */
    public function adminRejectBorrowRequest($id, Request $request)
    {
        try {
            $borrowRequest = \App\Models\BorrowRequest::findOrFail($id);
            
            if ($borrowRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ]);
            }

            $borrowRequest->update([
                'status' => 'rejected',
                'admin_notes' => $request->reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Borrow request rejected successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error rejecting borrow request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject request. Please try again.'
            ], 500);
        }
    }

    /**
     * Mark a borrow request as returned
     */
    public function adminMarkReturned($id)
    {
        try {
            $borrowRequest = \App\Models\BorrowRequest::findOrFail($id);
            
            if ($borrowRequest->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved requests can be marked as returned.'
                ]);
            }

            $borrowRequest->update([
                'status' => 'returned',
                'returned_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item marked as returned successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error marking as returned: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as returned. Please try again.'
            ], 500);
        }
    }
}
