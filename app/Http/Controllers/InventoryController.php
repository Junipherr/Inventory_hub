<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

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
        $items = Item::with('units')->get();
        return view('custodian.scanner', compact('items'));
    }

    public function show(Item $item)
    {
        return view('custodian.inventory.show', compact('item'));
    }

    public function dashboard()
    {
        // Fetch distinct departments
        $departments = DB::table('items')
            ->select('department')
            ->distinct()
            ->pluck('department');

        // For each department, fetch distinct categories
        $departmentCategories = [];
        foreach ($departments as $department) {
            $categories = DB::table('items')
                ->where('department', $department)
                ->select('category_id')
                ->distinct()
                ->pluck('category_id');
            $departmentCategories[$department] = $categories;
        }

        // Fetch items grouped by department
        $itemsByDepartment = [];
        foreach ($departments as $department) {
            $items = Item::where('department', $department)->get();
            $itemsByDepartment[$department] = $items;
        }

        return view('custodian.dashboard', compact('departments', 'departmentCategories', 'itemsByDepartment'));
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

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['quantity'] = 1;

        $item = Item::create($validated);

        // Generate QR code data (e.g., encode item info as JSON)
        $qrData = json_encode([
            'id' => $item->id,
            'item_name' => $item->item_name,
            'department' => $item->department,
            'category_id' => $item->category_id,
            'description' => $item->description,
        ]);

        // Save QR code data to item
        $item->qr_code = $qrData;
        $item->save();

        // Create a default unit for the new item
        $item->units()->create([
            'unit_number' => '1', // Default unit number, can be adjusted as needed
            'last_checked_at' => null,
        ]);

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
            'department' => 'required|string|max:255',
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
        ]);

        $checkedUnitIds = $validated['checked_units'] ?? [];
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
                $unit->save();
            }
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

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->generate($qrData);

        return response($qrCode)->header('Content-Type', 'image/png');
    }
}
