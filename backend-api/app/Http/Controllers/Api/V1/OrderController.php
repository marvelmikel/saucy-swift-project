<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\CustomerAddress;
use App\Model\DMReview;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderLogic::track_order($request['order_id']), 200);
    }

    public function place_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_amount' => 'required',
            'delivery_address_id' => 'required',
            'order_type' => 'required',
            'branch_id' => 'required',
            'delivery_time' => 'required',
            'delivery_date' => 'required',
            'distance' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        //order scheduling
        if ($request['delivery_time'] == 'now') {
            $del_date = Carbon::now()->format('Y-m-d');
            $del_time = Carbon::now()->format('H:i:s');
        } else {
            $del_date = $request['delivery_date'];
            $del_time = $request['delivery_time'];
        }

        try {
            $or = [
                'id' => 100000 + Order::all()->count() + 1,
                'user_id' => $request->user()->id,
                'order_amount' => Helpers::set_price($request['order_amount']),
                'coupon_discount_amount' => Helpers::set_price($request->coupon_discount_amount),
                'coupon_discount_title' => $request->coupon_discount_title == 0 ? null : 'coupon_discount_title',
                'payment_status' => ($request->payment_method=='cash_on_delivery')?'unpaid':'paid',
                'order_status' => ($request->payment_method=='cash_on_delivery')?'pending':'confirmed',
                'coupon_code' => $request['coupon_code'],
                'payment_method' => $request->payment_method,
                'transaction_reference' => $request->transaction_reference ?? null,

                'order_note' => $request['order_note'],

                'order_type' => $request['order_type'],
                'branch_id' => $request['branch_id'],
                'delivery_address_id' => $request->delivery_address_id,

                'delivery_date' => $del_date,
                'delivery_time' => $del_time,
                'delivery_address' => json_encode(CustomerAddress::find($request->delivery_address_id) ?? null),

                'delivery_charge' => Helpers::get_delivery_charge($request['distance']),
                'preparation_time' => Helpers::get_business_settings('default_preparation_time') ?? 0,

                'created_at' => now(),
                'updated_at' => now()
            ];

            $o_id = DB::table('orders')->insertGetId($or);

            foreach ($request['cart'] as $c) {
                $product = Product::find($c['product_id']);
                if (array_key_exists('variation', $c) && count(json_decode($product['variations'], true)) > 0) {
                    $price = Helpers::variation_price($product, json_encode($c['variation']));
                } else {
                    $price = Helpers::set_price($product['price']);
                }
                $or_d = [
                    'order_id' => $o_id,
                    'product_id' => $c['product_id'],
                    'product_details' => $product,
                    'quantity' => $c['quantity'],
                    'price' => $price,
                    'tax_amount' => Helpers::tax_calculate($product, $price),
                    'discount_on_product' => Helpers::discount_calculate($product, $price),
                    'discount_type' => 'discount_on_product',
                    'variant' => json_encode($c['variant']),
                    'variation' => array_key_exists('variation', $c) ? json_encode($c['variation']) : json_encode([]),
                    'add_on_ids' => json_encode($c['add_on_ids']),
                    'add_on_qtys' => json_encode($c['add_on_qtys']),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                DB::table('order_details')->insert($or_d);

                //update product popularity point
                Product::find($c['product_id'])->increment('popularity_count');
            }

            $fcm_token = $request->user()->cm_firebase_token;
            $value = Helpers::order_status_update_message(($request->payment_method=='cash_on_delivery')?'pending':'confirmed');
            try {
                //send push notification
                if ($value) {
                    $data = [
                        'title' => translate('Order'),
                        'description' => $value,
                        'order_id' => $o_id,
                        'image' => '',
                        'type'=>'order_status',
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }

                //send email
                $emailServices = Helpers::get_business_settings('mail_config');
                if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                    Mail::to($request->user()->email)->send(new \App\Mail\OrderPlaced($o_id));
                }

            } catch (\Exception $e) {

            }

            return response()->json([
                'message' => translate('order_success'),
                'order_id' => $o_id
            ], 200);

        } catch (\Exception $e) {
            return response()->json([$e], 403);
        }
    }

    public function get_order_list(Request $request)
    {
        $orders = Order::with(['customer', 'delivery_man.rating'])
            ->withCount('details')
            ->where(['user_id' => $request->user()->id])->get();

        $orders->map(function ($data) {
            $data['deliveryman_review_count'] = DMReview::where(['delivery_man_id' => $data['delivery_man_id'], 'order_id' => $data['id']])->count();

            //is product available
            $order_id = $data->id;
            $order_details = OrderDetail::where('order_id', $order_id)->first();
            $product_id = null;
            $product = null;
            if(isset($order_details))
                $product_id = $order_details->product_id;

            if(isset($product_id))
                $product = Product::find($product_id);

            $data['is_product_available'] = isset($product) ? 1 : 0;


            return $data;
        });

        return response()->json($orders->map(function ($data) {
            $data->details_count = (integer)$data->details_count;
            return $data;
        }), 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $details = OrderDetail::with('order')->where(['order_id' => $request['order_id']])->get();

        if ($details->count() > 0) {
            foreach ($details as $det) {
                $det['add_on_ids'] = json_decode($det['add_on_ids']);
                $det['add_on_qtys'] = json_decode($det['add_on_qtys']);

                $det['variation'] = json_decode($det['variation'], true);
                if ($det->order->order_type == 'pos') {
                    if(isset($det['variation'][0])) {
                        $det['variation'] = implode('-', array_values($det['variation'][0])) ?? null;
                    } else {
                        $det['variation'] = implode('-', array_values($det['variation'])) ?? null;
                    }
                }
                else {
                    if (isset($det['variation'][0])) {
                        $det['variation'] = !empty($det['variation'][0]) ? (string)$det['variation'][0]['type'] : null;
                    } else {
                        $det['variation'] = !empty($det['variation']) ? (string)$det['variation']['type'] : null;
                    }
                }

                $det['review_count'] = Review::where(['order_id' => $det['order_id'], 'product_id' => $det['product_id']])->count();
                $product = json_decode($det['product_details'], true);
                $det['product_details'] = isset($product) ? Helpers::product_data_formatting($product) : '';

                $product_availability = Product::where('id', $det['product_id'])->first();
                $det['is_product_available'] = isset($product_availability) ? 1 : 0;
            }
            return response()->json($details, 200);
        } else {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('not found!')]
                ]
            ], 404);
        }
    }

    public function cancel_order(Request $request)
    {
        if (Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first()) {
            Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->update([
                'order_status' => 'canceled'
            ]);
            return response()->json(['message' => translate('order_canceled')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('no_data_found')]
            ]
        ], 401);
    }

    public function update_payment_method(Request $request)
    {
        if (Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first()) {
            Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->update([
                'payment_method' => $request['payment_method']
            ]);
            return response()->json(['message' => translate('payment_method_updated')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('no_data_found')]
            ]
        ], 401);
    }
}
