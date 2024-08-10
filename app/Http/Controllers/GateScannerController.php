<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleOwner;
use App\Models\Vehicle;
use App\Models\Transaction; // Import the Transaction model
use App\Models\UserLog; // Import the UserLog model
use Illuminate\Support\Facades\Log;

class GateScannerController extends Controller
{
    public function scanQR(Request $request)
    {
        $qrCodeData = $request->input('qr_code');
        Log::info('Received QR code data: ' . $qrCodeData);
        
        try {
            // Find the vehicle owner
            $vehicleOwner = VehicleOwner::where('id', $qrCodeData)->first();
            Log::info('Vehicle owner found: ' . ($vehicleOwner ? 'Yes' : 'No'));

            if (!$vehicleOwner) {
                Log::warning('Vehicle owner not found for QR code: ' . $qrCodeData);
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle owner not found.'
                ]);
            }

            // Get vehicles associated with the owner
            $vehicles = Vehicle::where('vehicle_owner_id', $vehicleOwner->id)->get();
            
            // Fetch transactions for these vehicles
            $transactions = Transaction::whereIn('vehicle_id', $vehicles->pluck('id'))->get();

            // Extract the relevant details
            $vehicleData = $vehicles->map(function($vehicle) use ($transactions) {
                $transaction = $transactions->firstWhere('vehicle_id', $vehicle->id);
                return [
                    'plate_no' => $vehicle->plate_no,
                    'registration_no' => $transaction ? $transaction->registration_no : 'N/A',
                    'sticker_expiry' => $transaction ? $transaction->sticker_expiry : 'N/A'
                ];
            });

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
                'vehicles' => $vehicleData
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
