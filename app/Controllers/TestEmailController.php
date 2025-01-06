<?php

namespace App\Controllers;

class TestEmailController extends BaseController
{
    public function sendTestEmail()
    {
        // Prepare email configuration array
        $mailConfig = [
            'mail_from_email' => 'info@echospace.co',
            'mail_from_name' => 'EchoSpace',
            'mail_recipient_email' => 'recipient@example.com', // Replace with an actual recipient email
            'mail_recipient_name' => 'Recipient Name',
            'mail_subject' => 'Test Email',
            'mail_body' => '<p>This is a test email sent from EchoSpace application.</p>',
        ];

        // Use the helper function to send email
        if (sendEmail($mailConfig)) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email. Check logs for details.";
        }
    }
}
