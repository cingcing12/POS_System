<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use KHQR\BakongKHQR;
use KHQR\Models\IndividualInfo;
use KHQR\Helpers\KHQRData;

class PaymentController extends Controller
{
    // CONFIGURATION
    // For Production use: https://api-bakong.nbc.org.kh
    private $bakongBaseUrl = "https://api-bakong.nbc.org.kh"; 
    
    // Your Merchant Details
    private $merchantConfig = [
        'bakongAccountId' => 'your_bakong_id', // e.g. name@bank_id
        'merchantName'    => 'My Coffee Shop',
        'merchantCity'    => 'Phnom Penh',
        'mobileNumber'    => '85512345678'
    ];

    // Your App Details (Required for Deep Link)
    private $appConfig = [
        'iconUrl'     => 'https://your-website.com/logo.png', 
        'appName'     => 'My App Name',
        'callbackUrl' => 'https://your-website.com/payment-success'
    ];

    public function createPayment(Request $request)
    {
        try {
            // --- STEP 1: Generate Raw KHQR String ---
            // using the khqr-gateway/bakong-khqr-php package
            $individualInfo = new IndividualInfo(
                $this->merchantConfig['bakongAccountId'],
                $this->merchantConfig['mobileNumber'], // Account Information
                $this->merchantConfig['merchantName'],
                $this->merchantConfig['merchantCity'],
                $request->input('amount', 1.00),
                KHQRData::CURRENCY_USD // or KHQRData::CURRENCY_KHR
            );

            // Generate the raw string (e.g. 0002010102...)
            $rawQrResponse = BakongKHQR::generateIndividual($individualInfo);
            $rawQrString = $rawQrResponse->getData()->qr;

            // --- STEP 2: Call Bakong API for Deep Link ---
            $deepLink = $this->getBakongDeepLink($rawQrString);

            return response()->json([
                'success' => true,
                'raw_qr' => $rawQrString,
                'deep_link' => $deepLink
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to call the generate_deeplink_by_qr endpoint
     */
    private function getBakongDeepLink($khqrString)
    {
        $response = Http::post("{$this->bakongBaseUrl}/v1/generate_deeplink_by_qr", [
            'qr' => $khqrString,
            'sourceInfo' => [
                'appIconUrl' => $this->appConfig['iconUrl'],
                'appName' => $this->appConfig['appName'],
                'appDeepLinkCallback' => $this->appConfig['callbackUrl']
            ]
        ]);

        // Check if the request failed at the network level
        if ($response->failed()) {
            throw new \Exception("Network Error: " . $response->body());
        }

        $data = $response->json();

        // Check the specific Bakong response code (0 = Success)
        if (isset($data['responseCode']) && $data['responseCode'] === 0) {
            return $data['data']['shortLink'];
        } else {
            throw new \Exception("Bakong API Error: " . ($data['responseMessage'] ?? 'Unknown Error'));
        }
    }
}