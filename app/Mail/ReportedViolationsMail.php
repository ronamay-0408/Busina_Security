<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;  // Import Carbon for date handling

class ReportedViolationsMail extends Mailable
{
    public $excelFile;

    public function __construct($excelFile)
    {
        $this->excelFile = $excelFile;
    }

    public function build()
    {
        // Get current date in YYYY-MM-DD format
        $date = Carbon::now()->format('Y-m-d');
        
        // Create the filename with the current date
        $filename = "Violation_Report_{$date}.xlsx";
        
        return $this->subject('Violation Report')
                    ->html('<html>
                                <body>
                                    <p>Dear BUHead,</p>
                                    <p>Attached is the violation report you requested.</p>
                                    <p>Best regards,</p>
                                    <p>Bicol University BUsina</p>
                                </body>
                            </html>')  // Inline HTML content for the email body
                    ->attachData($this->excelFile, $filename, [
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
    }
}

