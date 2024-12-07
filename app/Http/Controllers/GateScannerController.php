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
                        Log::info('User log ID: ' . $userLog->id);
        
                        return response()->json([
                            'success' => false,
                            'message' => "
                                <div class='unsolved-vio'>
                                    <p>This vehicle is leaving. Do you want to log the time-out?</p>
                                </div>
                            ",
                            'timeoutbutton' => true, // Flag to trigger SweetAlert buttons
                            'user_log_id' => $userLog->id, // Pass only the user log ID
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
                
                // Get all plate numbers of vehicles owned by the owner
                $allPlateNumbers = $vehicles->pluck('plate_no')->implode(', ');

                // If there are any vehicles with unresolved violations, flag the owner
                if ($vehiclesWithViolations->isNotEmpty()) {
                    // Get plate numbers for only the vehicles with unresolved violations
                    $vehiclePlateNumbersWithViolations = $vehiclesWithViolations->pluck('plate_no')->implode(', '); // Join plate numbers into a single string
                
                    return response()->json([
                        'success' => false,
                        'message' => "
                            <div class='unsolved-vio'>
                                <p>Registered vehicles : <span style='font-weight: bold;'>{$allPlateNumbers}</span></p>
                                <p>The vehicle owner is restricted from bringing vehicles with plate numbers
                                <span style='font-weight: bold;'>{$vehiclePlateNumbersWithViolations}</span> onto campus for 2 months due to unresolved violations.</p>
                            </div>
                        ",
                        'showButtons' => true, // Flag to show the buttons in the frontend
                        'plateNumbers' => $vehiclePlateNumbersWithViolations, // Pass the plate numbers for use in the frontend
                    ]);
                }

                // // Create a new log entry for time_in if no prior log exists
                // UserLog::create([
                //     'vehicle_owner_id' => $vehicleOwner->id,
                //     'log_date' => $currentDate,
                //     'time_in' => $currentTime
                // ]);

                // If no unresolved violations, check if it's a new time-in request
                $plateNumbers = $vehicles->pluck('plate_no')->implode(', ');

                return response()->json([
                    'success' => null, // Indicates decision is required
                    // 'message' => "Do you want to allow these vehicles: {$plateNumbers} inside the University Premises?",
                    'message' => "
                        <div class='unsolved-vio'>
                            <p>Registered vehicles : <span style='font-weight: bold;'>{$allPlateNumbers}</span></p>
                            <p>Check if the vehicle in use is registered.</p>
                            <h4>Owner : <span class='flname'>{$vehicleOwner->fname} {$vehicleOwner->lname}</span></h4>
                        </div>
                    ",
                    'timeinbutton' => true, // Flag to show the buttons in the frontend
                    'plateNumbers' => $plateNumbers,
                    'vehicleOwner' => [
                        'fname' => $vehicleOwner->fname,
                        'lname' => $vehicleOwner->lname,
                    ]
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
            return redirect()->route('head_index');
        }
    }

    public function logTimeout(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Received log timeout request:', $request->all());

        $userLogId = $request->input('user_log_id');
        $action = $request->input('action'); // 'allow' or 'deny'

        // Check if the user_log_id is passed and valid
        if (!$userLogId) {
            Log::warning('No user_log_id provided.');
            return response()->json([
                'success' => false,
                'message' => 'Invalid user log ID.',
            ]);
        }

        // Find the user log entry
        $userLog = UserLog::find($userLogId);

        if (!$userLog) {
            Log::warning('User log not found for ID: ' . $userLogId);  // Log if user log is not found
            return response()->json([
                'success' => false,
                'message' => 'Log entry not found.',
            ]);
        }

        // Process action: 'allow' or 'deny'
        if ($action === 'allow') {
            Log::info('Allowing time-out for user log ID: ' . $userLogId);

            // Update the time_out field
            $updateSuccess = $userLog->update(['time_out' => now()]);

            if ($updateSuccess) {
                Log::info('Time-out successfully logged for user log ID: ' . $userLogId);
                return response()->json([
                    'success' => true,
                    'message' => 'Time-out logged successfully.',
                ]);
            } else {
                Log::warning('Failed to log time-out for user log ID: ' . $userLogId);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to log time-out.',
                ]);
            }
        } elseif ($action === 'deny') {
            Log::info('Time-out denied for user log ID: ' . $userLogId);
            // Perform deny-related actions (e.g., logging or notifying)
            return response()->json([
                'success' => false,
                'message' => 'Time-out log denied by the user.',
            ]);
        }

        // Return error if action is invalid
        return response()->json([
            'success' => false,
            'message' => 'Invalid action.',
        ]);
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
