<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
</head>
<body>
    <table style="width: 100%; max-width: 600px; margin: 0 auto; padding: 20px;">
        <tr>
            <td style="text-align: center; padding-bottom: 20px;">
                <!-- <img src="{{ asset('path/to/your/logo.png') }}" alt="Logo" style="max-width: 200px;"> -->
            </td>
        </tr>
        <tr>
            <td>
                <h2>Welcome to Our Website!</h2>
                <p>Dear {{ $userName }},</p>
                <p>We are delighted to welcome you to our website. Thank you for joining us!</p>
                <p>If you have any questions or need assistance, feel free to contact us.</p>
                <p>Best regards,<br>Your Website Team</p>
            </td>
        </tr>
    </table>
</body>
</html>
