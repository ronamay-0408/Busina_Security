<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ViolationType;
use App\Models\Violation;
use App\Models\Vehicle;
use App\Models\Users; // Import Users model
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ViolationReportMail;

class ReportVehicleController extends Controller
{
    public function showForm()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {

            $violationTypes = ViolationType::all(); // Retrieve all violation types
            return view('report_vehicle', compact('violationTypes'));

        } else {
            // Redirect to index if not authorized
            // abort(403, 'Unauthorized action.');
            return redirect()->route('head_index');
        }
    }

    public function store(Request $request)
    {
        // Set the timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');
        
        // Validate the form input
        $validator = $request->validate([
            'plate_no' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'vio_type' => 'required|integer|exists:violation_type,id',
            'report_by' => 'required|integer|exists:authorized_user,id',
            'photo' => 'nullable|image'
        ]);
        
        // Check if the plate number exists in the vehicle table
        $vehicle = Vehicle::where('plate_no', $request->input('plate_no'))->first();
        
        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle with this plate number does not exist.'], 400);
        }
        
        // CHECK FOR DUPLICATES VIOLATION REPORT
        $existingViolation = Violation::where('plate_no', $request->input('plate_no'))
            ->where('violation_type_id', $request->input('vio_type'))
            ->where('remarks', 'Not been settled')
            ->whereDate('created_at', now()->toDateString())
            ->first();
        
        if ($existingViolation) {
            return response()->json(['error' => 'This violation has already been reported.'], 400);
        }
        
        // Default value for remarks
        $remarks = 'Not been settled';
        
        // Initialize proof image data
        $proofImageData = null;
        if ($request->hasFile('photo')) {
            $proofImage = $request->file('photo');
        
            // Read the contents of the image file and convert to binary
            $proofImageData = file_get_contents($proofImage->getRealPath());
        }
        
        // Create a new violation record
        $violation = Violation::create([
            'plate_no' => $request->input('plate_no'),
            'location' => $request->input('location'),
            'violation_type_id' => $request->input('vio_type'),
            'remarks' => $remarks,
            'proof_image' => $proofImageData, // Store the image data directly as BLOB
            'reported_by' => $request->input('report_by'),
            'vehicle_id' => $vehicle->id
        ]);
        
        // Retrieve the vehicle owner ID
        $vehicleOwnerId = $vehicle->vehicle_owner_id;
        
        // Retrieve the email of the vehicle owner
        $vehicleOwnerUser = Users::where('vehicle_owner_id', $vehicleOwnerId)->first();
        
        // Retrieve the penalty fee for the violation type
        $violationType = ViolationType::find($violation->violation_type_id);
        $penaltyFee = $violationType ? $violationType->penalty_fee : 'Unknown';
        
        if ($vehicleOwnerUser) {
            // Send email using the Mailable
            Mail::to($vehicleOwnerUser->email)->send(new ViolationReportMail($vehicleOwnerUser, $violation, $penaltyFee));
        } else {
            Log::warning("No user found for vehicle owner ID: $vehicleOwnerId");
        }
        
        // Return JSON response with success message
        return response()->json(['success' => 'Violation report submitted successfully.'], 200);
    }
}