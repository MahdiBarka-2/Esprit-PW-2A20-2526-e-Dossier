<?php
/**
 * SMSController - Handles sending verification codes via SMS Gateway
 */

class SMSController {
    // Replace with your actual Twilio / SMS Provider credentials
    private $sid = "ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
    private $token = "your_auth_token";
    private $from = "+1234567890"; // Your Twilio phone number

    /**
     * Sends an SMS message to a specific number
     */
    public function sendSMS($to, $message) {
        // Basic phone number validation/cleaning
        $to = preg_replace('/[^0-9+]/', '', $to);
        
        // --- LOGGING FOR DEBUGGING ---
        // Since we don't have real keys, we log it to a file so the user can see the code
        $log = "[" . date('Y-m-d H:i:s') . "] Sending SMS to $to: $message\n";
        file_put_contents(__DIR__ . '/../sms_log.txt', $log, FILE_APPEND);

        // --- REAL TWILIO IMPLEMENTATION (Commented out for safety) ---
        /*
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json";
        $data = [
            'From' => $this->from,
            'To' => $to,
            'Body' => $message
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->sid}:{$this->token}");
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
        */

        return true; // Simulate success
    }
}

/**
 * Procedural wrapper for easier use
 */
function sendVerificationSMS($phone, $code) {
    $sms = new SMSController();
    $message = "Your E-Dossier verification code is: $code. Valid for 5 minutes.";
    return $sms->sendSMS($phone, $message);
}
