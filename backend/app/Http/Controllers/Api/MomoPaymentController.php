<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MomoPaymentController extends Controller
{
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $endpoint;

    public function __construct()
    {
        $this->partnerCode = env('MOMO_PARTNER_CODE');
        $this->accessKey = env('MOMO_ACCESS_KEY');
        $this->secretKey = env('MOMO_SECRET_KEY');
        $this->endpoint = env('MOMO_ENDPOINT', 'https://test-payment.momo.vn');
    }

    public function createPayment(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1000',
                'orderId' => 'required|string',
                'orderInfo' => 'required|string',
                'returnUrl' => 'required|url',
                'notifyUrl' => 'required|url',
            ]);

            $orderId = $request->orderId;
            $orderInfo = $request->orderInfo;
            $amount = $request->amount;
            $returnUrl = $request->returnUrl;
            $notifyUrl = $request->notifyUrl;
            $requestId = time() . '';
            $extraData = '';

            // Create signature
            $rawHash = "accessKey=" . $this->accessKey .
                "&amount=" . $amount .
                "&extraData=" . $extraData .
                "&ipnUrl=" . $notifyUrl .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&partnerCode=" . $this->partnerCode .
                "&redirectUrl=" . $returnUrl .
                "&requestId=" . $requestId .
                "&requestType=captureWallet";

            $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

            $data = [
                'partnerCode' => $this->partnerCode,
                'accessKey' => $this->accessKey,
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $returnUrl,
                'ipnUrl' => $notifyUrl,
                'extraData' => $extraData,
                'requestType' => 'captureWallet',
                'signature' => $signature,
                'lang' => 'vi'
            ];

            $client = new Client();
            $response = $client->post($this->endpoint . '/v2/gateway/api/create', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);

            $result = json_decode($response->getBody(), true);

            if (!isset($result['payUrl'])) {
                throw new \Exception('Momo payment URL not found in response');
            }

            return response()->json([
                'status' => 'success',
                'payUrl' => $result['payUrl']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function handleCallback(Request $request)
    {
        try {
            $data = $request->all();

            // Verify signature
            $rawHash = "accessKey=" . $this->accessKey .
                "&amount=" . $data['amount'] .
                "&extraData=" . $data['extraData'] .
                "&message=" . $data['message'] .
                "&orderId=" . $data['orderId'] .
                "&orderInfo=" . $data['orderInfo'] .
                "&orderType=" . $data['orderType'] .
                "&partnerCode=" . $data['partnerCode'] .
                "&payType=" . $data['payType'] .
                "&requestId=" . $data['requestId'] .
                "&responseTime=" . $data['responseTime'] .
                "&resultCode=" . $data['resultCode'] .
                "&transId=" . $data['transId'];

            $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

            if ($signature !== $data['signature']) {
                throw new \Exception('Invalid signature');
            }

            if ($data['resultCode'] != 0) {
                throw new \Exception($data['message'] ?? 'Payment failed');
            }

            // Update order status
            $order = Order::find($data['orderId']);
            if (!$order) {
                throw new \Exception('Order not found');
            }

            $order->update([
                'payment_status' => 'completed',
                'transaction_id' => $data['transId'],
                'status' => 'processing'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment processed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}