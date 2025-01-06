<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }

        .email-header h2 {
            color: #333333;
            font-size: 24px;
            margin: 0;
        }

        .email-body {
            padding: 20px;
            color: #333333;
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

        .note {
            color: #666666;
            font-size: 14px;
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>New Contact Us Message</h2>
        </div>

        <div class="email-body">
            <p>Dear Admin,</p>

            <p>
                You have received a new message through the <b>Contact Us</b> form on the EchoSpace website.
            </p>

            <p>
                <b>Name:</b> <?= $mail_data['name'] ?><br>
                <b>Email:</b> <?= $mail_data['email'] ?><br>
                <b>Message:</b>
            </p>

            <p style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #007bff;">
                <?= nl2br($mail_data['message']) ?>
            </p>

            <p class="note">
                Please respond to the sender via their email address provided above.
            </p>
        </div>

        <div class="email-footer">
            <p>This email was automatically sent by the EchoSpace system. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
