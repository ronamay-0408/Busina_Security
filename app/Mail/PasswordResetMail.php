<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $authorizedUser;
    public $resetLink;

    /**
     * Create a new message instance.
     *
     * @param $authorizedUser
     * @param $resetLink
     */
    public function __construct($authorizedUser, $resetLink)
    {
        $this->authorizedUser = $authorizedUser;
        $this->resetLink = $resetLink;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
    {
        return $this->subject('Reset Password Link from BUsina')
                    ->html($this->getEmailContent());
    }

    /**
     * Get the HTML content for the email.
     *
     * @return string
     */
    private function getEmailContent()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        </head>
        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; font-weight: 500;">
            <div style="background-color: white; border-radius: 10px; width: 100%; max-width: 600px; margin: 20px auto; text-align: left;">
                <div style="background-color: #161a39; align-items: center; text-align: center; padding: 20px;">
                    <h3 style="color: white; font-size: 20px;">Please reset your password</h3>
                </div>
                <div style="padding: 40px;">
                    <p style="margin: 10px 0; color: #666666; font-size: 14px;">Hello <span style="font-weight: 600;">' . $this->authorizedUser->fname . ' ' . $this->authorizedUser->lname . '</span>,</p>
                    <p style="margin: 10px 0; color: #666666; font-size: 14px;">We have received a request to reset your password. If you did not initiate this request, please disregard this email.</p>
                    <p style="margin: 10px 0; color: #666666; font-size: 14px;">To reset your password, click on the button below:</p>
                    <div style="margin: 20px 0; text-align: center;">
                        <a href="' . $this->resetLink . '" style="background-color: #161a39; border: none; color: white; padding: 10px 30px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;">Reset Password</a>
                    </div>
                    <p style="margin: 10px 0; color: #666666; font-size: 14px;">If the button above does not work, you can ignore this email, and your password will remain unchanged.</p>
                    <p style="margin: 10px 0; color: #666666; font-size: 14px;">If you have any questions or need further assistance, please don\'t hesitate to contact us at <a href="mailto:businabicoluniversity@gmail.com" style="color: #161a39; text-decoration: none;">busina@gmail.com</a>.</p>
                    <p style="margin: 10px 0; color: #666666; font-size: 14px;">Best regards,<br><span style="font-weight: 600;">Bicol University BUsina</span></p>
                </div>
                <div style="background-color: #161a39; padding: 20px 20px 5px 20px;">
                    <div style="color: #f4f4f4; font-size: 12px;">
                        <p><span style="font-size: 14px; font-weight: 600;">Contact</span></p>
                        <p>businabicoluniversity@gmail.com</p>
                        <p>Legazpi City, Albay, Philippines 13°08′39″N 123°43′26″E</p>
                    </div>
                    <div style="text-align: center;">
                        <p style="color: #f4f4f4; font-size: 14px;">Company © All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }
}
