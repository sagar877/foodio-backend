<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    public function createOrder(Request $request)
    {
        $amount = $request->input('amount');

        try {
            $orderData = [
                'receipt' => uniqid(),
                'amount' => $amount * 100, // Amount in paise
                'currency' => 'INR',
            ];

            $order = $this->api->order->create($orderData);
            return response()->json(['order' => $order , 'key' => env('RAZORPAY_KEY')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
