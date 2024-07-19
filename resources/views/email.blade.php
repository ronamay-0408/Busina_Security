<html>
    <head>
        <link rel="stylesheet" href="{{ asset('css/email_style.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body>
        <div class='email-container'>
            <div class="headmail">
                <img src="{{ asset('lockreload.png') }}" alt="">
                <h3>Please reset your password</h3>
            </div>
            <div class="semi-mail">
                <p>Hello <span>{$user->fname} {$user->lname}</span>,</p>
                <p>We have received a request to reset your password. If you did not initiate this request, please disregard this email.</p>
                <p>To reset your password, click on the button below:</p>
                <div class='button-container'>
                    <a href='{$resetLink}' class='button'>Reset Password</a>
                </div>
                <p>If the button above does not work, you can ignore this email, and your password will remain unchanged.</p>
                <p>If you have any questions or need further assistance, please don't hesitate to contact us at <a href='mailto:busina@gmail.com'>busina@gmail.com</a>.</p>
                <p>Best regards,<br><span>Bicol University BUsina</span></p>
            </div>
            <div class="mail-footer">
                <div class="mail-footer_up">
                    <p><span>Contact</span></p>
                    <p>busina@gmail.com</p>
                    <p>Legazpi City , Albay , Philippines 13°08′39″N 123°43′26″E</p>
                </div>
                <div class="mail-footer_down">
                    <p>Company ©  All Rights Reserved</p>
                </div>
            </div>
        </div>
    </body>
</html>