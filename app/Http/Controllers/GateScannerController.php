<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleOwner;
use App\Models\Vehicle;
use App\Models\UserLog; // Import the UserLog model
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class GateScannerController extends Controller
{
    public function scanQR(Request $request)
    {
        $qrCodeData = $request->input('qr_code');
        Log::info('Received QR code data: ' . $qrCodeData);
        
        try {
            $vehicleOwner = VehicleOwner::where('id', $qrCodeData)->first();
            Log::info('Vehicle owner found: ' . ($vehicleOwner ? 'Yes' : 'No'));

            if (!$vehicleOwner) {
                Log::warning('Vehicle owner not found for QR code: ' . $qrCodeData);
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle owner not found.'
                ]);
            }

            // Store vehicle owner ID in session
            session(['vehicle_owner_id' => $vehicleOwner->id]);
            Log::info('Vehicle owner ID stored in session: ' . $vehicleOwner->id);

            // Get current time
            $currentDate = now()->toDateString();
            $currentTime = now()->toTimeString();

            // Create or update the user log
            UserLog::updateOrCreate(
                [
                    'vehicle_owner_id' => $vehicleOwner->id,
                    'log_date' => $currentDate,
                ],
                [
                    'time_in' => $currentTime
                ]
            );

            return response()->json([
                'success' => true,
                'vehicleOwner' => $vehicleOwner,
                'vehicles' => Vehicle::where('vehicle_owner_id', $vehicleOwner->id)->get()
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing QR code: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the QR code.'
            ]);
        }
    }
}
