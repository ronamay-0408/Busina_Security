<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleOwner;
use App\Models\Vehicle;
use App\Models\Transaction; // Import the Transaction model
use App\Models\UserLog; // Import the UserLog model
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GateScannerController extends Controller
{
    public function scanQR(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {

            $qrCodeData = $request->input('qr_code');
            Log::info('Received QR code data: ' . $qrCodeData);

            try {
                // Find the vehicle owner
                // $vehicleOwner = VehicleOwner::where('id', $qrCodeData)->first(); //Code using vehicle_owner id
                $vehicleOwner = VehicleOwner::where('driver_license_no', $qrCodeData)->first(); //Code using drivers license
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

                // Get current date and time
                $currentDate = now()->toDateString();
                $currentTime = now()->toTimeString();

                // Check if there's an existing log entry without time_out for today
                $userLog = UserLog::where('vehicle_owner_id', $vehicleOwner->id)
                    ->where('log_date', $currentDate)
                    ->whereNull('time_out')
                    ->first();

                if ($userLog) {
                    // Fetch the relevant transaction for the vehicle
                    $transaction = $transactions->first(); // Assuming the first transaction is relevant

                    // Use a placeholder if no transaction is found
                    $registrationNo = $transaction ? $transaction->registration_no : 'Unknown';

                    // Update time_out and return the alert message
                    $userLog->update(['time_out' => $currentTime]);

                    return response()->json([
                        'success' => true,
                        'message' => "Vehicle with Registration Number {$registrationNo} is Leaving the University Premises"
                    ]);
                }

                // Create a new log entry for time_in
                UserLog::create([
                    'vehicle_owner_id' => $vehicleOwner->id,
                    'log_date' => $currentDate,
                    'time_in' => $currentTime
                ]);

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
        } else {
            // Redirect to index if not authorized
            // abort(403, 'Unauthorized action.');
            return redirect()->route('head_index');
        }
    }
}
