<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleOwner;
use App\Models\Vehicle;
use App\Models\Violation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Import the Session facade
use App\Models\Transaction; // Import the Transaction model
use Illuminate\Support\Facades\Auth;

class QRController extends Controller
{
    public function scanQR(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {

            $qrCodeData = $request->input('qr_code');
            Log::info('Received QR code data: ' . $qrCodeData);
            
            try {
                // $vehicleOwner = VehicleOwner::where('id', $qrCodeData)->first(); //Code using vehicle_owner id
                $vehicleOwner = VehicleOwner::where('driver_license_no', $qrCodeData)->first(); //Code using drivers license
                Log::info('Vehicle owner found: ' . ($vehicleOwner ? 'Yes' : 'No'));

                if (!$vehicleOwner) {
                    Log::warning('Vehicle owner not found for QR code: ' . $qrCodeData);
                    return response()->json([
                        'success' => false,
                        'message' => 'Vehicle owner not found.',
                        'redirect' => route('reg_not_found') // Include redirection URL
                    ]);
                }

                session(['vehicle_owner_id' => $vehicleOwner->id]);
                Log::info('Vehicle owner ID stored in session: ' . $vehicleOwner->id);

                return response()->json(['success' => true]);

            } catch (\Exception $e) {
                Log::error('Error processing QR code: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing the QR code.'
                ]);
            }

        } else {
            // Redirect to head_index if not authorized
            return redirect()->route('head_index');
        }
    }

    public function showResult()
    {
        $vehicleOwnerId = Session::get('vehicle_owner_id');

        if (!$vehicleOwnerId) {
            return redirect()->route('scanned_qr')->with('error', 'You need to scan a QR code first.');
        }

        $vehicleOwner = VehicleOwner::find($vehicleOwnerId);
        if (!$vehicleOwner) {
            return redirect()->route('scanned_qr')->with('error', 'Vehicle owner not found.');
        }

        // Fetch unique vehicles owned by the vehicle owner
        $vehicles = Vehicle::where('vehicle_owner_id', $vehicleOwnerId)->distinct()->get();
        
        // If no vehicles found, handle appropriately
        if ($vehicles->isEmpty()) {
            return redirect()->route('scanned_qr')->with('error', 'No vehicles found for this owner.');
        }

        // First, get the latest transaction ID for each registration_no
        $latestTransactions = Transaction::selectRaw('MAX(id) as latest_id')
            ->whereIn('vehicle_id', $vehicles->pluck('id'))
            ->groupBy('registration_no');
        
        // Join back to the transactions table to get full transaction details for each latest transaction
        $transactions = Transaction::whereIn('id', $latestTransactions->pluck('latest_id'))->get();
        
        // Group transactions by registration_no to pass to the view
        $groupedTransactions = $transactions->groupBy('registration_no');

        // Fetch unsettled violations for the user's vehicles
        $unsettledViolations = Violation::whereIn('vehicle_id', $vehicles->pluck('id'))
            ->where('remarks', 'Not been settled')
            ->with(['violationType', 'reportedBy'])
            ->get();

        return view('scanned_result', [
            'vehicleOwner' => $vehicleOwner,
            'vehicles' => $vehicles,
            'groupedTransactions' => $groupedTransactions,
            'unsettledViolations' => $unsettledViolations
        ]);
    }

    public function showVehicleInfo($registration_no)
    {
        // Fetch the transaction by registration number
        $transaction = Transaction::where('registration_no', $registration_no)->first();

        if (!$transaction) {
            return redirect()->route('scanned.result')->with('error', 'Vehicle not found.');
        }

        // Fetch the vehicle associated with the transaction
        $vehicle = Vehicle::find($transaction->vehicle_id);

        if (!$vehicle) {
            return redirect()->route('scanned.result')->with('error', 'Vehicle not found.');
        }

        $vehicleOwner = VehicleOwner::find($vehicle->vehicle_owner_id);

        return view('vehicle_registered_info', [
            'vehicle' => $vehicle,
            'vehicleOwner' => $vehicleOwner,
            'transaction' => $transaction
        ]);
    }
}

