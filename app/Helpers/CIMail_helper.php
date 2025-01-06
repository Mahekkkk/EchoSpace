<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('sendEmail')) {
    function sendEmail($mailConfig)
    {
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0; // Disable debug output
            $mail->isSMTP(); // Use SMTP
            $mail->Host = env('EMAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('EMAIL_USERNAME');
            $mail->Password = env('EMAIL_PASSWORD');
            $mail->SMTPSecure = strtolower(env('EMAIL_ENCRYPTION')); // Use TLS/SSL
            $mail->Port = env('EMAIL_PORT');

            // Use default or provided values
            $mail->setFrom(
                $mailConfig['mail_from_email'] ?? env('EMAIL_DEFAULT_FROM_EMAIL'),
                $mailConfig['mail_from_name'] ?? env('EMAIL_DEFAULT_FROM_NAME')
            );

            // Add recipient
            $mail->addAddress(
                $mailConfig['mail_recipient_email'] ?? throw new Exception('Recipient email not provided'),
                $mailConfig['mail_recipient_name'] ?? ''
            );

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $mailConfig['mail_subject'] ?? 'No Subject';
            $mail->Body = $mailConfig['mail_body'] ?? '';

            // Send email
            return $mail->send();
        } catch (Exception $e) {
            error_log('Email could not be sent: ' . $e->getMessage());
            return false;
        }
    }
}
