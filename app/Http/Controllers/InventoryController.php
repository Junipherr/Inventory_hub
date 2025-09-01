<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Style\Paper;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Item::with('units')->paginate(10);
        $rooms = Room::all();
        return view('custodian.inventory.items', compact('items', 'rooms'));
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

            $item->update($validated);

            // Always return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item updated successfully!',
                    'item' => $item
                ]);
            }

            return redirect()->route('inventory.items')->with('success', 'Item updated successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Always return JSON for AJAX requests, even for validation errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            // Always return JSON for AJAX requests, even for server errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating item: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating item: ' . $e->getMessage());
        }
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
            
            // Search in both items table and item_units table for QR codes
            $item = Item::where('qr_code', $qrCode)
                        ->with(['room', 'units'])
                        ->first();
            
            // If not found in items table, check item_units
            if (!$item) {
                $unit = \App\Models\ItemUnit::where('qr_code', $qrCode)
                                           ->with('item.room')
                                           ->first();
                
                if ($unit && $unit->item) {
                    $item = $unit->item;
                }
            }

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

    /**
     * Display available items with quantities for custodian/admin
     * This function provides a comprehensive view of items available for borrowing
     */
    public function displayAvailableItems()
    {
        // Get all items with proper available quantity calculation
        $availableItems = Item::with(['room', 'units'])
            ->get()
            ->map(function ($item) {
                // Calculate total quantity from items table
                $totalQuantity = $item->quantity ?? 0;
                
                // Calculate quantity that is already borrowed via approved requests
                $approvedBorrowQuantity = \App\Models\BorrowRequest::where('item_id', $item->id)
                    ->where('status', 'approved')
                    ->sum('quantity');
                
                // Calculate actual available quantity
                $availableQuantity = max(0, $totalQuantity - $approvedBorrowQuantity);
                
                // Count individual units that are available
                $availableUnits = $item->units->where('status', 'available')->count();
                
                // Add calculated properties to the item
                $item->available_quantity = $availableQuantity;
                $item->total_quantity = $totalQuantity;
                $item->available_units = $availableUnits;
                $item->is_available = $availableQuantity > 0 || $availableUnits > 0;
                
                return $item;
            })
            ->filter(function ($item) {
                return $item->is_available;
            })
            ->sortByDesc('available_quantity');

        // Create summary statistics
        $summary = [
            'total_available_items' => $availableItems->count(),
            'total_available_quantity' => $availableItems->sum('available_quantity'),
            'total_available_units' => $availableItems->sum('available_units'),
            'categories' => $availableItems->groupBy('category_id')->map->count(),
            'rooms' => $availableItems->groupBy('room_id')->map->count(),
        ];

        return view('custodian.available-items', compact('availableItems', 'summary'));
    }

    /**
     * Export inventory data for a specific room as Word document with pagination
     */
    public function exportWord($roomId)
    {
        // Get the same data as the dashboard
        $user = auth()->user();
        if ($user->role === 'Viewer') {
            abort(403, 'Access denied. Viewers cannot access the custodian dashboard.');
        }

        // Get the specific room
        $room = Room::with('users')->findOrFail($roomId);

        // Get items for the specific room
        $items = Item::with('units')->where('room_id', $room->id)->get();

        // Get person in charge for the room
        $custodian = $room->users->firstWhere('role', 'Custodian');
        if (!$custodian) {
            $custodian = $room->users->first();
        }
        $personInCharge = $custodian ?? null;

        // Create new Word document
        $phpWord = new PhpWord();

        // Define font styles (Times New Roman, 12pt)
        $titleFontStyle  = ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'];
        $boldFontStyle   = ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'];
        $normalFontStyle = ['size' => 12, 'name' => 'Times New Roman'];

        // Section with margins and paper size (in twips: 1 inch = 1440 twips)
        $section = $phpWord->addSection([
            'paperSize' => 'Legal',
            'marginTop'    => 1440, // 2.54 cm
            'marginBottom' => 0,    // 0.0 cm
            'marginLeft'   => 1440, // 2.54 cm
            'marginRight'  => 1080, // 1.905 cm
        ]);

        // Title
        $section->addText('MEMORANDUM RECEIPT OF PROPERTIES', $titleFontStyle, ['alignment' => 'center']);
        $section->addText('First Semester, A.Y. 2025-2026', $boldFontStyle, ['alignment' => 'center']);
        $section->addTextBreak(2);

        // Room/Department
        $section->addText('Room/Department: ' . $room->name, $normalFontStyle);
        $section->addTextBreak(1);

        // Items per page
        $itemsPerPage = 15;
        $itemChunks = $items->chunk($itemsPerPage);

        foreach ($itemChunks as $chunkIndex => $chunk) {
            if ($chunkIndex > 0) {
                // Add page break for subsequent chunks
                $section->addPageBreak();
            }

            // Create table for items (no borders)
            $table = $section->addTable(['borderSize' => 0, 'borderColor' => 'ffffff']);

            // Table headers
            $table->addRow();
            $table->addCell(2000)->addText('QUANTITY', $boldFontStyle);
            $table->addCell(4000)->addText('PARTICULARS', $boldFontStyle);
            $table->addCell(3000)->addText('REMARKS', $boldFontStyle);

            // Add rows for each item in the chunk
            foreach ($chunk as $item) {
                // Determine item status/remarks based on condition or availability
                $remarks = 'Good Condition'; // Default remark
                if ($item->condition && strtolower($item->condition) !== 'good') {
                    $remarks = ucfirst($item->condition);
                }

                $table->addRow();
                $table->addCell()->addText($item->quantity, $normalFontStyle);
                $table->addCell()->addText($item->item_name, $normalFontStyle);
                $table->addCell()->addText($remarks, $normalFontStyle);
            }

            $section->addTextBreak(2);
        }

        // Footer text (paragraphs) - only after the last table
        $section->addText(
            "The properties listed above are dully-turned over to the undersigned for official use only. The undersigned binds himself/herself to take good care of the said properties while on his/her position/control or until this Memorandum Receipt is revoked. Any losses or damages resulting from negligence or improper use by the undersigned or the other parties under him/her will be charged to his/her account. The properties listed above shall not be used or brought outside the campus without permission from the Property Custodian and the College President.",
            $normalFontStyle
        );
        $section->addTextBreak(1);

        $section->addText(
            "The above stated properties shall not be transferred to any office or brought outside the premises of the college compound except with the written permission from the Property Custodian and the College President.",
            $normalFontStyle
        );
        $section->addTextBreak(2);

        // Signatures
        $section->addText("Properties received by:", $boldFontStyle);
        $section->addText($personInCharge ? $personInCharge->name : 'N/A' . "\t\t\t\t\t\tDate received: " . date('F d, Y'), $normalFontStyle);
        $section->addText("Classroom In-charge", $normalFontStyle);
        $section->addTextBreak(2);

        $section->addText("Endorsed by:", $boldFontStyle);
        $section->addText("VERGELIO O. CABAHUG", $normalFontStyle);
        $section->addText("Property Custodian", $normalFontStyle);
        $section->addTextBreak(2);

        $section->addText("Approved by:", $boldFontStyle);
        $section->addText("MARIANO JOAQUIN C. MACIAS JR., Ed.D", $normalFontStyle);
        $section->addText("College President/Chairman, BOT", $normalFontStyle);

        // Save the document
        $filename = 'memorandum_receipt_' . str_replace(' ', '_', $room->name) . '_' . date('Y-m-d_H-i-s') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'word_') . '.docx';

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        // Return the file as download
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Fallback export method when template processing fails
     */
    private function exportWordFallback($room, $items, $personInCharge)
    {
        // Create new Word document
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Add title
        $section->addText(
            'Inventory Report - ' . $room->name . ' - ' . date('Y-m-d'),
            ['bold' => true, 'size' => 16],
            ['alignment' => 'center']
        );
        $section->addTextBreak(2);

        // Room header
        $section->addText(
            "Room: {$room->name}",
            ['bold' => true, 'size' => 14]
        );

        // Person in charge
        $section->addText(
            "Person in Charge: " . ($personInCharge ? $personInCharge->name : 'N/A'),
            ['size' => 12]
        );

        // Room statistics
        $section->addText(
            "Total Items: {$items->count()} | Total Quantity: {$items->sum('quantity')}",
            ['size' => 12]
        );
        $section->addTextBreak(1);

        // Items per page
        $itemsPerPage = 15;
        $itemChunks = $items->chunk($itemsPerPage);

        foreach ($itemChunks as $chunkIndex => $chunk) {
            if ($chunkIndex > 0) {
                // Add page break for subsequent chunks
                $section->addPageBreak();
            }

            // Add items table if room has items (no borders)
            if ($chunk->isNotEmpty()) {
                $table = $section->addTable(['borderSize' => 0, 'borderColor' => 'ffffff']);

                // Table headers
                $table->addRow();
                $table->addCell(2000)->addText('Item Name', ['bold' => true]);
                $table->addCell(1500)->addText('Category', ['bold' => true]);
                $table->addCell(1000)->addText('Quantity', ['bold' => true]);
                $table->addCell(3000)->addText('Description', ['bold' => true]);

                // Table rows
                foreach ($chunk as $item) {
                    $table->addRow();
                    $table->addCell()->addText($item->item_name);
                    $table->addCell()->addText(ucwords(str_replace('_', ' ', $item->category_id)));
                    $table->addCell()->addText($item->quantity);
                    $table->addCell()->addText($item->description);
                }
            } else {
                $section->addText('No items in this room.', ['italic' => true]);
            }

            $section->addTextBreak(2);
        }

        // Save the document
        $filename = 'inventory_report_' . str_replace(' ', '_', $room->name) . '_' . date('Y-m-d_H-i-s') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'word_') . '.docx';

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        // Return the file as download
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
