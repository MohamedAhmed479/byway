<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .otp-box {
            background-color: #fff;
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 5px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Byway</div>
            <h1>Email Verification</h1>
        </div>
        
        <p>Hello <?= esc($username ?? 'User') ?>,</p>
        
        <p>Thank you for registering with Byway! To complete your registration, please use the verification code below:</p>
        
        <div class="otp-box">
            <p>Your verification code is:</p>
            <div class="otp-code"><?= esc($otp) ?></div>
            <p><small>This code will expire in 5 minutes</small></p>
        </div>
        
        <div class="warning">
            <strong>Important:</strong> Never share this verification code with anyone. Byway will never ask for your verification code via email or phone.
        </div>
        
        <p>If you didn't create an account with Byway, please ignore this email.</p>
        
        <div class="footer">
            <p>Best regards,<br>The Byway Team</p>
            <p><small>This is an automated message, please do not reply to this email.</small></p>
        </div>
    </div>
</body>
</html>
