<?php
/**
 * EmailController - Real-time Email Delivery via Gmail SMTP
 */

// Include PHPMailer classes
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController
{
    /**
     * 🔑 YOUR GMAIL CONFIGURATION
     */
    private $gmail_address = "barkamahdi04@gmail.com"; // <--- CHANGE THIS to your real Gmail
    private $app_password = "gava ytfi lsum xlkd";      // <--- Your 16-digit App Password

    /**
     * Sends a real email using Gmail SMTP
     */
    public function sendEmail($to, $subject, $message)
    {
        // ALWAYS log the code locally first (safe fallback)
        $log = "[" . date('Y-m-d H:i:s') . "] 2FA Code for $to: " . strip_tags($message) . "\n";
        file_put_contents(__DIR__ . '/../mail_log.txt', $log, FILE_APPEND);

        if ($this->gmail_address === "YOUR_GMAIL_HERE@gmail.com") {
            return true; // Fallback to logging if email not set
        }

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $this->gmail_address;
            $mail->Password = $this->app_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom($this->gmail_address, 'E-Dossier Security');
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            file_put_contents(__DIR__ . '/../mail_log.txt', "Mail Error: " . $mail->ErrorInfo . "\n", FILE_APPEND);
            return false;
        }
    }
}

/**
 * Procedural wrapper for easier use
 */
function sendVerificationEmail($email, $code)
{
    $mail = new EmailController();
    $subject = "E-Dossier Security Code: $code";
    $message = "
        <div style='font-family: Helvetica, Arial, sans-serif; min-width: 1000px; overflow: auto; line-height: 2'>
          <div style='margin: 50px auto; width: 70%; padding: 20px 0'>
            <div style='border-bottom: 1px solid #eee'>
              <a href='' style='font-size: 1.4em; color: #066ac9; text-decoration: none; font-weight: 600'>E-Dossier</a>
            </div>
            <p style='font-size: 1.1em'>Hi,</p>
            <p>Thank you for choosing E-Dossier. Use the following OTP to complete your Log In procedures. OTP is valid for 5 minutes</p>
            <h2 style='background: #066ac9; margin: 0 auto; width: max-content; padding: 0 10px; color: #fff; border-radius: 4px;'>$code</h2>
            <p style='font-size: 0.9em;'>Regards,<br />E-Dossier Security Team</p>
            <hr style='border: none; border-top: 1px solid #eee' />
          </div>
        </div>
    ";
    return $mail->sendEmail($email, $subject, $message);
}
