<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;

class PaymentController extends Controller
{
    public function handlePayment(Request $request)
    {
        if($request->type == 'donation'){
            $amount = round((floatval($request->amount)),2);
            $return_url = route('success.payment');
        }else{
            $cartId = $request->cart_id;
            $cart = Cart::with(['detail' => function ($query) {
                    $query->with(['product' => function ($q) {
                        $q->with('images');
                    }])->with(['color','size']);
                }])
                ->where('id',$cartId)
                ->first();
            $amount = round((floatval($cart->total_amount)+floatval($cart->delivery_carge)),2);

            $return_url = route('success.payment').'?cart_id='.$cartId;
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                // "return_url" => route('success.payment'),
                "return_url" => $return_url,
                "cancel_url" => route('cancel.payment'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $amount
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()
                ->route('cancel.payment')
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->route('paypal.error')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function paymentCancel()
    {
        return redirect()
            ->route('paypal.error')
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }

    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            $cartId = $request->cart_id;
            if($cartId){
                $cart = Cart::with(['detail' => function ($query) {
                        $query->with(['product' => function ($q) {
                            $q->with('images');
                        }])->with(['color','size']);
                    }])
                    ->where('id',$cartId)
                    ->first();
                $amount = round((floatval($cart->total_amount)+floatval($cart->delivery_carge)),2);

                $transaction = Transaction::create([
                    'transaction_id' => $response['id'],
                    'payment_method' => 'paypal',
                    'user_id' => $cart->user_id,
                    'amount' => $amount,
                    'response' => json_encode($response)
                ]);

                $orderId = 'ORD'.rand(1111111,9999999);
                $order = Order::create([
                    'order_number' => $orderId,
                    'transaction_id' => $transaction->id,
                    'user_id' => $cart->user_id,
                    'address_id' => $cart->address_id,
                    'total_amount' => $amount,
                    'status' => 'completed' 
                ]);

                foreach ($cart->detail as $key => $value) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $value->product_id,
                        'price' => $value->price,
                        'qty' => $value->qty,
                        'color' => $value->color,
                        'size' => $value->size
                    ]);
                }

                Cart::where('id',$cartId)->delete();
                CartDetail::where('cart_id',$cartId)->delete();
            }

            return redirect()
                ->route('paypal.success')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('paypal.error')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }
}
