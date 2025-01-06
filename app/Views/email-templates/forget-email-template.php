<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .email-header h2 {
            color: #333333;
            font-size: 24px;
            margin: 0;
        }

        .email-body {
            padding: 20px;
            color: #555555;
            line-height: 1.6;
        }

        .email-body p {
            margin: 15px 0;
        }

        .action-button {
            text-align: center;
            margin: 30px 0;
        }

        .action-button a {
            display: inline-block;
            padding: 12px 25px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }

        .action-button a:hover {
            background-color: #0056b3;
        }

        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Password Reset Request</h2>
        </div>

        <div class="email-body">
            <p>Hi <strong><?= esc($mail_data['user']->name) ?></strong>,</p>

            <p>We received a request to reset your password for the EchoSpace account associated with <em><?= esc($mail_data['user']->email) ?></em>.</p>

            <p>If you made this request, please click the button below to reset your password:</p>

            <div class="action-button">
                <a href="<?= esc($mail_data['actionLink']) ?>" target="_blank">Reset Password</a>
            </div>

            <p>If you did not request a password reset, please ignore this email. This link will expire in 24 hours.</p>
        </div>

        <div class="email-footer">
            <p>This email was sent automatically by the EchoSpace system. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
