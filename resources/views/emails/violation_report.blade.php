<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violation Report Notification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; font-weight: 500;">
    <div style="background-color: white; border-radius: 10px; width: 100%; max-width: 600px; margin: 20px auto; text-align: left;">
        <div style="background-color: #161a39; align-items: center; text-align: center; padding: 20px;">
            <h3 style="color: white; font-size: 20px;">Violation Report Submitted</h3>
        </div>
        <div style="padding: 40px;">
            <p style="margin: 10px 0; color: #666666; font-size: 14px;">Hello <span style="font-weight: 600;">{{ $user->fname }} {{ $user->lname }}</span>,</p>
            <p style="margin: 10px 0; color: #666666; font-size: 14px;">A new violation report has been submitted for your vehicle with the following details:</p>
            <p style="margin: 10px 0; color: #666666; font-size: 14px;"><strong>Location:</strong> {{ $violation->location }}</p>
            <p style="margin: 10px 0; color: #666666; font-size: 14px;"><strong>Date and Time:</strong> {{ $violation->created_at }}</p>
            <p style="margin: 10px 0; color: #666666; font-size: 14px;"><strong>Penalty Fee:</strong> ₱{{ $penaltyFee }}</p>
            <p style="margin: 10px 0; color: #666666; font-size: 14px;">Don't forget to settle your violation as soon as possible to lessen inconvenience on your Vehicle Renewal.</p>
            <p style="margin: 10px 0; color: #666666; font-size: 14px;">If you have any questions or need further assistance, please contact us at <a href="mailto:businabicoluniversity@gmail.com" style="color: #161a39; text-decoration: none;">busina@gmail.com</a>.</p>
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
</html>
