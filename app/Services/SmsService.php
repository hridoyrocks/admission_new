<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiUrl;
    protected $apiKey;
    protected $senderId;
    protected $enabled;

    public function __construct()
    {
        // BulkSMSBD specific configuration
        $this->apiUrl = config('services.sms.url', 'http://bulksmsbd.net/api/smsapi');
        $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.sender_id', '8809617612445');
        $this->enabled = config('services.sms.enabled', true);
    }

    public function send($phone, $message)
    {
        if (!$this->enabled) {
            Log::info('SMS service is disabled');
            return true;
        }

        if (empty($this->apiKey)) {
            Log::error('SMS API key is missing');
            return false;
        }

        try {
            $formattedPhone = $this->formatPhoneNumber($phone);
            
            // BulkSMSBD API parameters
            $params = [
                'api_key' => $this->apiKey,
                'type' => 'text',  // BulkSMSBD requires this
                'number' => $formattedPhone,  // Note: 'number' not 'numbers'
                'senderid' => $this->senderId,
                'message' => $message
            ];

            Log::info('Sending SMS via BulkSMSBD', [
                'url' => $this->apiUrl,
                'params' => array_merge($params, ['api_key' => 'HIDDEN']),
            ]);

            // Method 1: Using HTTP GET (as per BulkSMSBD documentation)
            $response = Http::get($this->apiUrl, $params);

            Log::info('BulkSMSBD Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            // BulkSMSBD returns JSON response
            $responseData = $response->json();

            // Check response based on BulkSMSBD format
            if (isset($responseData['response_code']) && $responseData['response_code'] == 202) {
                Log::info('SMS sent successfully', [
                    'message_id' => $responseData['message_id'] ?? null,
                    'success_message' => $responseData['success_message'] ?? null
                ]);
                return true;
            }

            // Check for error messages
            if (isset($responseData['error_message'])) {
                Log::error('BulkSMSBD Error', [
                    'error' => $responseData['error_message'],
                    'response_code' => $responseData['response_code'] ?? null
                ]);
            }

            return false;

        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    protected function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // BulkSMSBD accepts numbers in format: 8801XXXXXXXXX
        if (strlen($phone) == 10) {
            // If 10 digits (1712345678), add 880
            $phone = '880' . $phone;
        } elseif (strlen($phone) == 11 && substr($phone, 0, 1) == '0') {
            // If 11 digits starting with 0 (01712345678), replace 0 with 880
            $phone = '880' . substr($phone, 1);
        } elseif (strlen($phone) == 13 && substr($phone, 0, 3) == '880') {
            // Already in correct format
            // Do nothing
        }
        
        return $phone;
    }

    public function test($phone)
    {
        $message = "Test SMS from Banglay IELTS\nTime: " . now()->format('Y-m-d H:i:s');
        
        Log::info('Starting SMS test', [
            'phone' => $phone,
            'formatted_phone' => $this->formatPhoneNumber($phone),
            'api_url' => $this->apiUrl,
            'sender_id' => $this->senderId,
            'api_key_length' => strlen($this->apiKey)
        ]);

        return $this->send($phone, $message);
    }
}