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

class ReportVehicleController extends Controller
{
    public function showForm()
    {
        $violationTypes = ViolationType::all(); // Retrieve all violation types
        return view('report_vehicle', compact('violationTypes'));
    }

    public function store(Request $request)
    {
        // Set the timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');

        // Validate the form input
        $request->validate([
            'plate_no' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'vio_type' => 'required|integer|exists:violation_type,id',
            'report_by' => 'required|integer|exists:authorized_user,id',
            'photo' => 'nullable|image'
        ]);

        // Check if the plate number exists in the vehicle table
        $vehicle = Vehicle::where('plate_no', $request->input('plate_no'))->first();

        if (!$vehicle) {
            return redirect()->back()->with('error', 'Vehicle with this plate number does not exist.');
        }

        // Handle file upload
        $proofImagePath = null;
        if ($request->hasFile('photo')) {
            $proofImage = $request->file('photo');
            $proofImagePath = $proofImage->storeAs('proof_images', $proofImage->getClientOriginalName(), 'public');
        }

        // Default value for remarks
        $remarks = 'Not been settled';

        // Create a new violation record
        $violation = Violation::create([
            'plate_no' => $request->input('plate_no'),
            'location' => $request->input('location'),
            'violation_type_id' => $request->input('vio_type'),
            'remarks' => $remarks,
            'proof_image' => $proofImagePath,
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
            // Send email with violation details
            $this->sendViolationEmail($vehicleOwnerUser, $violation, $penaltyFee);
        } else {
            Log::warning("No user found for vehicle owner ID: $vehicleOwnerId");
        }

        // Redirect with success message
        return redirect()->route('report.vehicle.form')->with('success', 'Violation report submitted successfully.');
    }

    private function sendViolationEmail($user, $violation, $penaltyFee)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ronabalangat2003@gmail.com'; // Your Gmail address
            $mail->Password   = 'dsae bzxj zikj tbxy';        // Your Gmail password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('busina@example.com', 'BUsina');
            $mail->addAddress($user->email);  // Add recipient email

            // Content
            $mail->isHTML(true); // Set to true if sending HTML email
            $mail->Subject = 'Violation Report Notification';
            $mail->Body    = "
            <html>
            <head>
                <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'>
            </head>
            <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; font-weight: 500;'>
                <div style='background-color: white; border-radius: 10px; width: 100%; max-width: 600px; margin: 20px auto; text-align: left;'>
                    <div style='background-color: #161a39; align-items: center; text-align: center; padding: 20px;'>
                        <h3 style='color: white; font-size: 20px;'>Violation Report Submitted</h3>
                    </div>
                    <div style='padding: 40px;'>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Hello <span style='font-weight: 600;'>{$user->fname} {$user->lname}</span>,</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>A new violation report has been submitted for your vehicle with the following details:</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'><strong>Location:</strong> {$violation->location}</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'><strong>Date and Time:</strong> {$violation->created_at}</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'><strong>Proof Image:</strong> <a href='" . asset('storage/' . $violation->proof_image) . "'>View Image</a></p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'><strong>Penalty Fee:</strong> ₱{$penaltyFee}</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Don't forget to settle your violation as soon as possible to lessen inconvenience on your Vehicle Renewal.</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>If you have any questions or need further assistance, please contact us at <a href='mailto:busina@gmail.com' style='color: #161a39; text-decoration: none;'>busina@gmail.com</a>.</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Best regards,<br><span style='font-weight: 600;'>Bicol University BUsina</span></p>
                    </div>
                    <div style='background-color: #161a39; padding: 20px 20px 5px 20px;'>
                        <div style='color: #f4f4f4; font-size: 12px;'>
                            <p><span style='font-size: 14px; font-weight: 600;'>Contact</span></p>
                            <p>busina@gmail.com</p>
                            <p>Legazpi City, Albay, Philippines 13°08′39″N 123°43′26″E</p>
                        </div>
                        <div style='text-align: center;'>
                            <p style='color: #f4f4f4; font-size: 14px;'>Company © All Rights Reserved</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->send();
        } catch (Exception $e) {
            // Handle errors
            Log::error("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}
