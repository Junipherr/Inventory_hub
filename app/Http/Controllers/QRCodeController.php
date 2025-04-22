<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    // Display QR Code Generator view
    public function showGenerator()
    {
        return view('qr.generate');
    }

    // Generate QR code based on user input
    public function generate(Request $request)
    {
        $text = $request->input('text');
        $qrCode = QrCode::size(300)->generate($text);
        return view('qr.generate', compact('qrCode', 'text'));
    }

    // Show the QR scanner view
    public function showScanner()
    {
        return view('qr.scan');
    }

    // Optionally handle scanned data
    public function storeScan(Request $request)
    {
        $code = $request->input('code');
        // Save to DB or process as needed
        return response()->json(['message' => 'Scanned QR code: ' . $code]);
    }
}
