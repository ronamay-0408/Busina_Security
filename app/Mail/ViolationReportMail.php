<?php

namespace App\Mail;

use App\Models\Violation;
use App\Models\Users;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ViolationReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $violation;
    public $penaltyFee;

    public function __construct(Users $user, Violation $violation, $penaltyFee)
    {
        $this->user = $user;
        $this->violation = $violation;
        $this->penaltyFee = $penaltyFee;
    }

    public function build()
    {
        $subject = 'Violation Report Notification';

        $email = $this->view('emails.violation_report') // View for the email content
                     ->subject($subject)
                     ->with([
                         'user' => $this->user,
                         'violation' => $this->violation,
                         'penaltyFee' => $this->penaltyFee,
                     ]);

        // Attach the image from the database if it exists
        if ($this->violation->proof_image) {
            // Get the image binary data from the database
            $imageData = $this->violation->proof_image;

            // Create a temporary file
            $tempImagePath = tempnam(sys_get_temp_dir(), 'proof_image') . '.jpg';
            file_put_contents($tempImagePath, $imageData);

            // Attach the image to the email
            $email->attach($tempImagePath, [
                'as' => 'violation_proof_image.jpg', // The filename
                'mime' => 'image/jpeg', // MIME type for the image
            ]);
        }

        return $email;
    }
}
