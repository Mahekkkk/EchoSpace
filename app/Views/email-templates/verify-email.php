<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add custom styling here */
        .verify-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-content">
        <h2>Verify Your Email Address</h2>
        <p>Hi <?= esc($user['name'] ?? 'User') ?>,</p>
        <p>Thank you for registering with us. Please click the button below to verify your email address:</p>
        <!-- Correct URL generation using the appropriate base URL -->
        <a href="<?= esc($actionLink) ?>" class="verify-btn">Verify Email</a>
        <p>If the button above doesn't work, you can also copy and paste the following URL into your browser:</p>
        <p><a href="<?= esc($actionLink) ?>"><?= esc($actionLink) ?></a></p>

        <div class="footer">
            <p>If you did not register for this account, please ignore this email.</p>
        </div>
    </div>
</body>
</html>
