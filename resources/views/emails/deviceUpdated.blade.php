<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            font-size: 16px;
            line-height: 1.5;
        }
        .content h1 {
            color: #333333;
        }
        .content p {
            color: #555555;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ $message->embed(public_path('SCNLogo.gif')) }}" alt="App Logo">
        </div>
        <div class="content">
            <h1>Device Event Notification</h1>
            <p>Hello,</p>
            <p>We wanted to let you know that your <strong>{{ $deviceName }}</strong></p>
            <button>Dismiss</button>
            <button>Request Assistance</button>
            <p>Thank you for using our services.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} SCN Security. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
