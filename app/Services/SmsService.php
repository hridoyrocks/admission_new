<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $client;
    protected $apiUrl;
    protected $apiKey;
    protected $senderId;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = config('services.sms.url');
        $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.sender_id');
    }

    public function send($phone, $message)
    {
        try {
            // For Bangladesh SMS Gateway (e.g., SSL Wireless, Banglalink, etc.)
            $response = $this->client->post($this->apiUrl, [
                'form_params' => [
                    'api_key' => $this->apiKey,
                    'senderid' => $this->senderId,
                    'numbers' => $this->formatPhoneNumber($phone),
                    'message' => $message,
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            
            if ($result['status'] == 'success') {
                Log::info('SMS sent successfully', ['phone' => $phone]);
                return true;
            }

            Log::error('SMS sending failed', ['response' => $result]);
            return false;

        } catch (\Exception $e) {
            Log::error('SMS sending error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Add country code if not present
        if (substr($phone, 0, 3) !== '880') {
            $phone = '880' . ltrim($phone, '0');
        }
        
        return $phone;
    }
}