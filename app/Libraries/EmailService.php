<?php

namespace App\Libraries;

use Config\EmailService as EmailServiceConfig;

/**
 * Custom Email Service
 * 
 * This service handles sending emails through the custom email API
 * using cURL. It can be used for OTP, password reset, notifications, etc.
 */
class EmailService
{
    protected EmailServiceConfig $config;

    public function __construct(?EmailServiceConfig $config = null)
    {
        $this->config = $config ?? config('EmailService');
    }

    /**
     * Send an email
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $message Email message body (can be HTML or plain text)
     * @param string|null $name Optional sender name (defaults to config senderName)
     * @return array Result array with 'success' boolean and 'message' string
     */
    public function sendEmail(string $to, string $subject, string $message, ?string $name = null): array
    {
        if (empty($this->config->secret)) {
            log_message('error', 'EmailService: Secret key is not configured');
            return [
                'success' => false,
                'message' => 'Email service is not properly configured'
            ];
        }

        if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Invalid email address'
            ];
        }

        $name = $name ?? $this->config->senderName;
        
        // Generate hash as per API requirements
        $hash = md5(substr(hash('sha512', $this->config->secret . '-' . $to . '-' . $subject . '-' . $message), 4, 32));

        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->config->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $this->config->timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'name'      => $name,
                'hash'      => $hash,
                'to'        => $to,
                'subject'   => $subject,
                'message'   => $message,
            ],
        ]);

        $startTime = microtime(true);
        $response = curl_exec($curl);
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // Duration in milliseconds
        
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlInfo = curl_getinfo($curl);
        $error = curl_error($curl);
        
        // Log detailed request/response information
        log_message('info', "EmailService Request Details:");
        log_message('info', "  URL: {$this->config->apiUrl}");
        log_message('info', "  To: {$to}");
        log_message('info', "  Subject: {$subject}");
        log_message('info', "  Duration: {$duration}ms");
        log_message('info', "  HTTP Code: {$httpCode}");
        log_message('info', "  cURL Error: " . ($error ?: 'None'));
        log_message('info', "  Response Length: " . strlen($response) . " bytes");
        log_message('info', "  Response: " . substr($response, 0, 500)); // First 500 chars of response
        if ($httpCode === 0) {
            log_message('error', "  Connection failed - cURL Error Number: " . curl_errno($curl));
            log_message('error', "  Total Time: " . ($curlInfo['total_time'] ?? 'N/A'));
            log_message('error', "  Connect Time: " . ($curlInfo['connect_time'] ?? 'N/A'));
            log_message('error', "  Name Lookup Time: " . ($curlInfo['namelookup_time'] ?? 'N/A'));
        }
        
        curl_close($curl);

        if ($error) {
            log_message('error', "EmailService cURL error after {$duration}ms: {$error}");
            return [
                'success' => false,
                'message' => 'Failed to send email: ' . $error,
                'duration_ms' => $duration,
                'http_code' => $httpCode,
                'response' => $response
            ];
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            log_message('info', "EmailService: Email sent successfully to {$to} with subject '{$subject}' in {$duration}ms");
            log_message('debug', "EmailService Response: " . substr($response, 0, 500));
            return [
                'success' => true,
                'message' => 'Email sent successfully',
                'response' => $response,
                'duration_ms' => $duration,
                'http_code' => $httpCode
            ];
        }

        log_message('error', "EmailService: Failed to send email after {$duration}ms. HTTP Code: {$httpCode}");
        log_message('error', "EmailService Response: " . substr($response, 0, 1000));
        return [
            'success' => false,
            'message' => 'Failed to send email. Server returned error.',
            'http_code' => $httpCode,
            'response' => $response,
            'duration_ms' => $duration
        ];
    }

    /**
     * Send OTP email
     * 
     * @param string $to Recipient email address
     * @param string $otp OTP code
     * @return array Result array
     */
    public function sendOTP(string $to, string $otp): array
    {
        $subject = 'Your OTP Code';
        $message = "Your OTP code is: <strong>{$otp}</strong><br><br>";
        $message .= "This code will expire in 10 minutes. Please do not share this code with anyone.";
        
        return $this->sendEmail($to, $subject, $message);
    }

    /**
     * Send password reset email
     * 
     * @param string $to Recipient email address
     * @param string $resetLink Password reset link
     * @param string|null $name Optional recipient name
     * @return array Result array
     */
    public function sendPasswordReset(string $to, string $resetLink, ?string $name = null): array
    {
        $subject = 'Reset Your Password';
        $message = "Hello " . ($name ?? 'User') . ",<br><br>";
        $message .= "You requested to reset your password. Click the link below to reset it:<br><br>";
        $message .= "<a href=\"{$resetLink}\" style=\"display:inline-block;padding:10px 20px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:5px;\">Reset Password</a><br><br>";
        $message .= "Or copy and paste this link into your browser:<br>";
        $message .= "<small>{$resetLink}</small><br><br>";
        $message .= "This link will expire in 1 hour. If you didn't request this, please ignore this email.";
        
        return $this->sendEmail($to, $subject, $message, $name);
    }

    /**
     * Send welcome/registration email
     * 
     * @param string $to Recipient email address
     * @param string $name Recipient name
     * @return array Result array
     */
    public function sendWelcomeEmail(string $to, string $name): array
    {
        $subject = 'Welcome to PlayPass!';
        $message = "Hello {$name},<br><br>";
        $message .= "Thank you for registering with PlayPass! We're excited to have you on board.<br><br>";
        $message .= "You can now start exploring our amazing products and offers.<br><br>";
        $message .= "If you have any questions, feel free to contact our support team.<br><br>";
        $message .= "Best regards,<br>The PlayPass Team";
        
        return $this->sendEmail($to, $subject, $message, $name);
    }
}
