<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Model\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Redirect;
use Session;

class RazorPayController extends Controller
{
    public function __construct()
    {
        $razor = Helpers::get_business_settings('razor_pay');
        if ($razor) {
            $config = array(
                'razor_key' => env('RAZOR_KEY', $razor['razor_key']),
                'razor_secret' => env('RAZOR_SECRET', $razor['razor_secret'])
            );
            Config::set('razor', $config);
        }
    }
    public function payWithRazorpay()
    {
        return view('razor-pay');
    }

    public function payment(Request $request)
    {
        $callback = $request['callback'];
        $amount = $request['order_amount'];

        //Input items of form
        $input = $request->all();
        //get API Configuration
        $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        //token string generate
        $transaction_reference = $input['razorpay_payment_id'];
        $token_string = 'payment_method=razor_pay&&transaction_reference=' . $transaction_reference;

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                //$response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $amount));
                //$order = Order::where(['id' => $response->description])->first();
                //$tr_ref = $input['razorpay_payment_id'];

            } catch (\Exception $e) {
                //fail
                if ($callback != null) {
                    return redirect($callback . '/fail' . '?token=' . base64_encode($token_string));
                } else {
                    return \redirect()->route('payment-fail', ['token' => base64_encode($token_string)]);
                }
            }
        }

        //success
        if ($callback != null) {
            return redirect($callback . '/success' . '?token=' . base64_encode($token_string));
        } else {
            return \redirect()->route('payment-success', ['token' => base64_encode($token_string)]);
        }

    }

}
