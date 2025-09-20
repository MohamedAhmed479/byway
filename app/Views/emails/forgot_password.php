<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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
        .reset-box {
            background-color: #fff;
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .reset-code {
            font-size: 32px;
            font-weight: bold;
            color: #dc3545;
            letter-spacing: 5px;
            margin: 10px 0;
        }
        .reset-button {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 15px 0;
        }
        .reset-button:hover {
            background-color: #c82333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .warning {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
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
            <h1>Password Reset Request</h1>
        </div>
        
        <p>Hello <?= esc($username ?? 'User') ?>,</p>
        
        <p>We received a request to reset your password for your Byway account. If you made this request, please use the reset code below:</p>
        
        <div class="reset-box">
            <p>Your password reset code is:</p>
            <div class="reset-code"><?= esc($otp) ?></div>
            <p><small>This code will expire in 15 minutes</small></p>
        </div>

        <div class="warning">
            <strong>Security Notice:</strong> 
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Never share this reset code with anyone</li>
                <li>Byway will never ask for your password or reset code via email or phone</li>
                <li>If you didn't request this password reset, please ignore this email</li>
                <li>Your password will remain unchanged until you complete the reset process</li>
            </ul>
        </div>
        
        <p>If you didn't request a password reset, please ignore this email and consider changing your password if you suspect unauthorized access to your account.</p>
        
        <div class="footer">
            <p>Best regards,<br>The Byway Team</p>
            <p><small>This is an automated message, please do not reply to this email.</small></p>
        </div>
    </div>
</body>
</html>
