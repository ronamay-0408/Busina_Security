<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleOwner;
use App\Models\Vehicle;
use App\Models\Transaction; // Import the Transaction model
use App\Models\UserLog; // Import the UserLog model
use App\Models\Violation; // Import the Violation model
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
                $vehicleOwner = VehicleOwner::where('driver_license_no', $qrCodeData)->first();
                Log::info('Vehicle owner found: ' . ($vehicleOwner ? 'Yes' : 'No'));

                if (!$vehicleOwner) {
                    Log::warning('Vehicle owner not found for QR code: ' . $qrCodeData);
                    return response()->json([
                        'success' => false,
                        'message' => 'Vehicle owner not found.'
                    ]);
                }

                // Get all vehicles owned by the vehicle owner
                $vehicles = $vehicleOwner->vehicles;

                // Get current date and time
                $currentDate = now()->toDateString();
                $currentTime = now()->toTimeString();

                // CHECK IF THERE'S AN EXISTING LOG ENTRY WITHOUT time_out FOR TODAY
                $userLog = UserLog::where('vehicle_owner_id', $vehicleOwner->id)
                    ->where('log_date', $currentDate)
                    ->whereNull('time_out')
                    ->first();

                if ($userLog) {
                    // Fetch the relevant transaction for the vehicle linked to this log entry
                    $transaction = Transaction::where('vehicle_id', $userLog->vehicle_id)->first();

                    // Use a placeholder if no transaction is found
                    if ($transaction) {
                        $registrationNo = $transaction->registration_no;
                    } else {
                        Log::warning('Transaction not found for vehicle ID: ' . $userLog->vehicle_id);
                        $registrationNo = 'Unknown';  // Handle the case where no transaction is found
                    }

                    // Update time_out and return the alert message
                    $userLog->update(['time_out' => $currentTime]);

                    return response()->json([
                        'success' => true,
                        'message' => "{$vehicleOwner->fname} {$vehicleOwner->lname} is Leaving the University Premises"
                    ]);
                }

                // If no time_out exists, proceed with checking unsettled violations
                $unsettledViolations = Violation::whereIn('vehicle_id', $vehicles->pluck('id'))
                    ->where('remarks', 'Not been settled')
                    ->where('created_at', '<=', now('UTC')->subDays(20)->startOfDay()) // Using UTC time zone
                    ->get();

                // Check if at least one vehicle has an unsettled violation
                $vehiclesWithViolations = $vehicles->filter(function ($vehicle) use ($unsettledViolations) {
                    return $unsettledViolations->contains('vehicle_id', $vehicle->id);
                });
                
                // If there are any vehicles with unresolved violations, flag the owner
                if ($vehiclesWithViolations->isNotEmpty()) {
                    // Get plate numbers for only the vehicles with unresolved violations
                    $vehiclePlateNumbersWithViolations = $vehiclesWithViolations->pluck('plate_no')->implode(', '); // Join plate numbers into a single string
                
                    return response()->json([
                        'success' => false,
                        'message' => "This vehicle owner cannot bring the vehicles with plate numbers {$vehiclePlateNumbersWithViolations} onto campus for 2 months due to unresolved violations.",
                        'showButtons' => true, // Flag to show the buttons in the frontend
                        'plateNumbers' => $vehiclePlateNumbersWithViolations, // Pass the plate numbers for use in the frontend
                    ]);
                }

                // Create a new log entry for time_in if no prior log exists
                UserLog::create([
                    'vehicle_owner_id' => $vehicleOwner->id,
                    'log_date' => $currentDate,
                    'time_in' => $currentTime
                ]);

                // Get all plate numbers of the vehicles owned by the vehicle owner
                $plateNumbers = $vehicles->pluck('plate_no')->implode(', ');

                // Modify the success message to include the plate numbers
                return response()->json([
                    'success' => true,
                    'message' => "Vehicle entry successful! Welcome {$vehicleOwner->fname} {$vehicleOwner->lname}. Vehicles: {$plateNumbers}"
                ]);

                // return response()->json([
                //     'success' => true,
                //     'message' => "Vehicle entry successful! Welcome {$vehicleOwner->fname} {$vehicleOwner->lname}."
                // ]);

            } catch (\Exception $e) {
                Log::error('Error processing QR code: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing the QR code.'
                ]);
            }
        } else {
            // Redirect to index if not authorized
            return redirect()->route('head_index');
        }
    }

    public function saveTimeIn(Request $request)
    {
        $plateNumbers = $request->input('plate_numbers');
    
        // Log the received data for debugging
        Log::info('Plate numbers: ' . $plateNumbers);
    
        // Assuming you have a way to identify the vehicle(s) based on plate numbers
        $vehicles = Vehicle::whereIn('plate_no', explode(',', $plateNumbers))->get();
    
        // Log the vehicles found for the plate numbers
        Log::info('Vehicles found: ' . $vehicles->pluck('plate_no')->implode(', '));
    
        if ($vehicles->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No vehicles found for the provided plate numbers.'
            ]);
        }
    
        // Save time_in for each vehicle
        foreach ($vehicles as $vehicle) {
            // Assuming UserLog has a vehicle_id and other relevant fields
            UserLog::create([
                'vehicle_owner_id' => $vehicle->vehicle_owner_id, // You should have this relation
                'vehicle_id' => $vehicle->id,
                'log_date' => now()->toDateString(),
                'time_in' => now()->toTimeString(),
            ]);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Vehicle entry has been recorded.'
        ]);
    }
    
    public function viewLogs(Request $request)
    {
        // Get today's date using Carbon
        $today = Carbon::today();  // This returns the current date (00:00:00 of today)

        // Create a query to fetch logs for today with vehicle owner details, ordered by log date and time in
        $query = UserLog::with('vehicleOwner')
            ->whereDate('log_date', $today)  // Only fetch records where the log_date is today's date
            ->orderBy('log_date', 'desc')    // Sort by log date in descending order
            ->orderBy('time_in', 'desc');    // Then by time_in in descending order

        // Fetch the results for today
        $userLog = $query->get();

        // Return the view with the logs
        return view('gate_scanner', ['userLog' => $userLog]);
    }
}
