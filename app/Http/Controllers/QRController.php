<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleOwner;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Import the Session facade

class QRController extends Controller
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
    }

    public function showResult()
    {
        // Use the Session facade to get the session data
        $vehicleOwnerId = Session::get('vehicle_owner_id');

        if (!$vehicleOwnerId) {
            return redirect()->route('scanned_qr')->with('error', 'You need to scan a QR code first.');
        }

        $vehicleOwner = VehicleOwner::find($vehicleOwnerId);
        if (!$vehicleOwner) {
            return redirect()->route('scanned_qr')->with('error', 'Vehicle owner not found.');
        }

        $vehicles = Vehicle::where('vehicle_owner_id', $vehicleOwnerId)->get();

        // Optionally clear session data
        // Session::forget('vehicle_owner_id');

        return view('scanned_result', [
            'vehicleOwner' => $vehicleOwner,
            'vehicles' => $vehicles
        ]);
    }

    public function showVehicleInfo($registration_no)
    {
        $vehicle = Vehicle::with('vehicleType')->where('registration_no', $registration_no)->first();
        if (!$vehicle) {
            return redirect()->route('scanned.result')->with('error', 'Vehicle not found.');
        }

        $vehicleOwner = VehicleOwner::find($vehicle->vehicle_owner_id);
        return view('vehicle_registered_info', [
            'vehicle' => $vehicle,
            'vehicleOwner' => $vehicleOwner
        ]);
    }
}
