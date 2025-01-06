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

        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            margin-top: 20px;
        }

        .highlight {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Password Changed Successfully</h2>
        </div>

        <div class="email-body">
            <p>Hi <strong><?= esc($mail_data['user']->name) ?></strong>,</p>

            <p>Your password for the EchoSpace account has been successfully updated. Below are your new login details:</p>



            <p>
                <strong>Login ID:</strong> <?= esc($mail_data['user']->username ?? $mail_data['user']->email) ?><br>
                <strong>New Password:</strong> <span class="highlight">
                    <?= isset($mail_data['new_password']) ? esc($mail_data['new_password']) : 'N/A' ?>
                </span>
            </p>


            <p class="note">Please keep your credentials confidential. Do not share them with anyone for security purposes.</p>
        </div>

        <div class="email-footer">
            <p>This email was sent automatically by the EchoSpace system. Please do not reply.</p>
        </div>
    </div>
</body>

</html>