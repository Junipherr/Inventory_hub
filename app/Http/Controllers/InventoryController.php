<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('custodian.inventory.items', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Item::create($validated);

        return redirect()->route('inventory.create')->with('success', 'Item added successfully.');
    }
}
