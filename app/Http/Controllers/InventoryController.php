<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Room;

use Illuminate\Support\Facades\DB;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Item::with('units')->get();
        return view('custodian.inventory.items', compact('items'));
    }

    public function scanner()
    {
        $items = Item::with(['units', 'room'])->get();
        
        // Determine person in charge for each room (user with role 'Custodian')
        $rooms = Room::with('users')->get();
        $personsInCharge = [];
        foreach ($rooms as $room) {
            $custodian = $room->users->firstWhere('role', 'Custodian');
            if (!$custodian) {
                // fallback to first user if no custodian found
                $custodian = $room->users->first();
            }
            $personsInCharge[$room->id] = $custodian ? $custodian->name : 'N/A';
        }
        
        return view('custodian.scanner', compact('items', 'personsInCharge'));
    }

    public function show(Item $item)
    {
        return view('custodian.inventory.show', compact('item'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        if ($user->role === 'Viewer') {
            abort(403, 'Access denied. Viewers cannot access the custodian dashboard.');
            // Alternatively, redirect to viewer dashboard placeholder route:
            // return redirect()->route('viewer.dashboard');
        }

        // Fetch distinct rooms with users eager loaded
        $rooms = Room::with('users')->get();

        // For each room, fetch distinct categories
        $roomCategories = [];
        foreach ($rooms as $room) {
            $categories = DB::table('items')
                ->where('room_id', $room->id)
                ->select('category_id')
                ->distinct()
                ->pluck('category_id');
            $roomCategories[$room->id] = $categories;
        }

        // Fetch items grouped by room with eager loading units
        $itemsByRoom = [];
        foreach ($rooms as $room) {
            $items = Item::with('units')->where('room_id', $room->id)->get();
            $itemsByRoom[$room->id] = $items;
        }

        // Determine person in charge for each room (user with role 'Custodian')
        $personsInCharge = [];
        foreach ($rooms as $room) {
            $custodian = $room->users->firstWhere('role', 'Custodian');
            if (!$custodian) {
                // fallback to first user if no custodian found
                $custodian = $room->users->first();
            }
            $personsInCharge[$room->id] = $custodian;
        }

        return view('custodian.dashboard', compact('rooms', 'roomCategories', 'itemsByRoom', 'personsInCharge'));
    }

    public function getItemsByCategory($categoryId)
    {
        $items = Item::where('category_id', $categoryId)->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        // Disable store method as item creation is moved to confirm
        return redirect()->route('inventory.create');
    }

    public function create()
    {
        $rooms = Room::all();
        return view('custodian.inventory.create', compact('rooms'));
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'room_id' => 'required|integer|exists:rooms,id',
            'category_id' => 'required|string|max:255',
            'description' => 'nullable|string',
            'qr_code' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::create($validated);

        // Save qr_code from request
        $item->qr_code = $validated['qr_code'];
        $item->save();

        // Create a default unit for the new item
        $item->units()->create([
            'unit_number' => '1', // Default unit number, can be adjusted as needed
            'last_checked_at' => null,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added successfully.',
                'item_id' => $item->id,
                'qr_code' => $validated['qr_code'],
            ]);
        }

        return redirect()->route('inventory.create')->with('success', 'Item added successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Item deleted successfully.']);
        }
        return redirect()->route('inventory.items')->with('success', 'Item deleted successfully.');
    }

    public function edit(Item $item)
    {
        return view('custodian.inventory.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'room_id' => 'required|integer|exists:rooms,id',
            'category_id' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $item->update($validated);

        return redirect()->route('inventory.items')->with('success', 'Item updated successfully.');
    }

    public function updateCheckedItems(Request $request)
    {
        $validated = $request->validate([
            'checked_units' => 'nullable|array',
            'checked_units.*' => 'integer|exists:item_units,id',
            'status' => 'nullable|array',
            'status.*' => 'string',
        ]);

        $checkedUnitIds = $validated['checked_units'] ?? [];
        $statusData = $validated['status'] ?? [];
        $now = now();

        $allUnitIds = \App\Models\ItemUnit::pluck('id')->toArray();

        foreach ($allUnitIds as $unitId) {
            $unit = \App\Models\ItemUnit::find($unitId);
            if ($unit) {
                if (in_array($unitId, $checkedUnitIds)) {
                    $unit->last_checked_at = $now;
                } else {
                    $unit->last_checked_at = null;
                }
                if (isset($statusData[$unitId])) {
                    $unit->status = $statusData[$unitId];
                }
                $unit->save();
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Checked units updated successfully.',
            ]);
        }

        return redirect()->route('scanner')->with('success', 'Checked units updated successfully.');
    }

    public function generateQRCode($id)
    {
        $item = Item::findOrFail($id);

        $qrData = $item->qr_code;

        if (!$qrData) {
            abort(404, 'QR code not found for this item.');
        }

        // For debugging: return raw qr_code data as plain text
        return response($qrData)->header('Content-Type', 'text/plain');

        /*
        try {
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->generate($qrData);
        } catch (\Exception $e) {
            \Log::error('QR code generation failed for item ID ' . $id . ': ' . $e->getMessage());
            abort(500, 'Failed to generate QR code.');
        }

        return response($qrCode)->header('Content-Type', 'image/png');
        */
    }
}
