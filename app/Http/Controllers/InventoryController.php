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
        
        // Determine person in charge for each room
        $rooms = Room::with('users')->get();
        $personsInCharge = [];
        foreach ($rooms as $room) {
            $custodian = $room->users->firstWhere('role', 'Custodian');
            if (!$custodian) {
                $custodian = $room->users->first();
            }
            $personsInCharge[$room->id] = $custodian ?? null;
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
        }

        $rooms = Room::with('users')->get();
        $roomCategories = [];
        foreach ($rooms as $room) {
            $categories = DB::table('items')
                ->where('room_id', $room->id)
                ->select('category_id')
                ->distinct()
                ->pluck('category_id');
            $roomCategories[$room->id] = $categories;
        }

        $itemsByRoom = [];
        foreach ($rooms as $room) {
            $items = Item::with('units')->where('room_id', $room->id)->get();
            $itemsByRoom[$room->id] = $items;
        }

        $personsInCharge = [];
        foreach ($rooms as $room) {
            $custodian = $room->users->firstWhere('role', 'Custodian');
            if (!$custodian) {
                $custodian = $room->users->first();
            }
            $personsInCharge[$room->id] = $custodian ?? null;
        }

        // Calculate total items count
        $totalItems = Item::count();

        return view('custodian.dashboard', compact('rooms', 'roomCategories', 'itemsByRoom', 'personsInCharge', 'totalItems'));
    }

    public function getItemsByCategory($categoryId)
    {
        $items = Item::where('category_id', $categoryId)->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_name' => 'required|string|max:255',
                'room_id' => 'required|integer|exists:rooms,id',
                'category_id' => 'required|string|max:255',
                'description' => 'nullable|string',
                'quantity' => 'required|integer|min:1',
                'qr_code' => 'nullable|string|max:255',
                'purchase_date' => 'nullable|date',
                'purchase_price' => 'nullable|numeric|min:0',
                'warranty_expires' => 'nullable|date',
                'condition' => 'nullable|string|max:255',
            ]);

            // Generate QR code if not provided
            if (empty($validated['qr_code'])) {
                $validated['qr_code'] = uniqid('item_');
            }

            $item = Item::create($validated);

            // Create default unit
            $item->units()->create([
                'unit_number' => '1',
                'last_checked_at' => null,
            ]);

            // Return JSON response for AJAX handling
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item "' . $item->item_name . '" added successfully!',
                    'item' => $item
                ]);
            }

            // Fallback for non-AJAX requests
            return redirect()->route('inventory.items')
                ->with('success', 'Item "' . $item->item_name . '" added successfully!');
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating item: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating item: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $rooms = Room::all();
        return view('custodian.inventory.create', compact('rooms'));
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
            'quantity' => 'required|integer|min:1',
            'qr_code' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'warranty_expires' => 'nullable|date',
            'condition' => 'nullable|string|max:255',
        ]);

        $item->update($validated);
        return redirect()->route('inventory.items')->with('success', 'Item updated successfully.');
    }

    public function updateCheckedItems(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|array',
            'status.*' => 'required|string|max:255',
        ]);

        try {
            foreach ($validated['status'] as $unitId => $status) {
                $unit = \App\Models\ItemUnit::findOrFail($unitId);
                $unit->status = $status;
                $unit->last_checked_at = now();
                $unit->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Changes saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving changes: ' . $e->getMessage()
            ], 422);
        }
    }

    public function handleManualQrInput(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string|max:255',
        ]);

        try {
            $qrCode = $validated['qr_code'];
            
            // Find item by QR code
            $item = Item::whereHas('units', function($query) use ($qrCode) {
                $query->where('qr_code', $qrCode);
            })->with(['units', 'room'])->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found with QR code: ' . $qrCode
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item found successfully',
                'item' => [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'room' => $item->room->name ?? 'N/A',
                    'qr_code' => $qrCode
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateQRCode($id)
    {
        try {
            // For direct data generation (not an item ID)
            if (strpos($id, 'ITEM-') === 0) {
                $qrData = $id;
            } else {
                $item = Item::findOrFail($id);
                $qrData = $item->qr_code;
            }

            $qrCode = QrCode::format('png')
                           ->size(300)
                           ->margin(2)
                           ->generate($qrData);

            return response($qrCode)
                   ->header('Content-Type', 'image/png')
                   ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        } catch (\Exception $e) {
            \Log::error('QR Code Generation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate QR code'], 500);
        }
    }
}
