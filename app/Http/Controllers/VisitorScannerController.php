<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Unauthorized;
use Carbon\Carbon;

class VisitorScannerController extends Controller
{
    public function scan(Request $request)
    {
        $qrCodeData = $request->input('qr_code');
        Log::info('QR Code Data Received:', ['qr_code' => $qrCodeData]);

        $visitorPattern = '/^visitor \d+$/i';

        if (preg_match($visitorPattern, $qrCodeData)) {
            Log::info('QR Code matched pattern:', ['qr_code' => $qrCodeData]);

            $unauthorizedRecord = Unauthorized::where('qrcode', $qrCodeData)
                ->whereNull('time_out')
                ->first();

            if ($unauthorizedRecord) {
                $unauthorizedRecord->update([
                    'time_out' => Carbon::now()->toTimeString()
                ]);

                return response()->json([
                    'message' => 'Plate Number : ' . $unauthorizedRecord->plate_no . ' is timed out.',
                    'redirect' => route('visitor_scanner')
                ]);
            } else {
                session(['qr' => $qrCodeData]);
                return response()->json(['redirect' => route('unauthorized')]);
            }
        } else {
            Log::info('QR Code did not match pattern:', ['qr_code' => $qrCodeData]);
            return response()->json(['redirect' => route('visitorcode_notfound')]);
        }
    }
}
