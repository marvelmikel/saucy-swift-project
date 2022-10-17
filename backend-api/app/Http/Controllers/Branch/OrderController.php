<?php

namespace App\Http\Controllers\Branch;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CentralLogics\translate;

class OrderController extends Controller
{
    public function list($status, Request $request)
    {
        Order::where(['checked' => 0, 'branch_id' => auth('branch')->id()])->update(['checked' => 1]);
        if ($status == 'all') {
            $orders = Order::with(['customer'])->where(['branch_id' => auth('branch')->id()]);
        } elseif ($status == 'schedule') {
            $orders = Order::whereDate('delivery_date','>', \Carbon\Carbon::now()->format('Y-m-d'))
                ->where(['branch_id' => auth('branch')->id()]);
        } else {
            $orders = Order::with(['customer'])
                ->where(['order_status' => $status, 'branch_id' => auth('branch')->id()])
                ->whereDate('delivery_date','<=',\Carbon\Carbon::now()->format('Y-m-d'));
        }

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $orders = Order::where(['branch_id' => auth('branch')->id()])
                ->whereDate('delivery_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_reference', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        }

        $orders = $orders->notPos()->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('branch-views.order.list', compact('orders', 'status', 'search'));
    }

    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $orders = Order::where(['branch_id' => auth('branch')->id()])->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('order_status', 'like', "%{$value}%")
                    ->orWhere('transaction_reference', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('branch-views.order.partials._table', compact('orders'))->render()
        ]);
    }

    public function details($id)
    {
        $order = Order::with('details')->where(['id' => $id, 'branch_id' => auth('branch')->id()])->first();

        if(!isset($order)) {
            Toastr::info(translate('No more orders!'));
            return back();
        }

        //remaining delivery time
        $delivery_date_time =  $order['delivery_date']. ' ' .$order['delivery_time'];
        $ordered_time = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($delivery_date_time)));
        $remaining_time = $ordered_time->add($order['preparation_time'], 'minute')->format('Y-m-d H:i:s');
        $order['remaining_time'] = $remaining_time;

        return view('branch-views.order.order-view', compact('order'));
    }

    public function status(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'branch_id' => auth('branch')->id()])->first();
        if (($request->order_status == 'delivered' || $request->order_status == 'out_for_delivery') && $order['delivery_man_id'] == null && $order['order_type'] != 'take_away') {
            Toastr::warning(translate('Please assign delivery man first!'));
            return back();
        }
        $order->order_status = $request->order_status;
        if($request->order_status == 'delivered') {
            $order->payment_status = 'paid';
        }
        $order->save();

        $fcm_token=null;
        if($order->customer) {
            $fcm_token = $order->customer->cm_firebase_token;
        }
        $value = Helpers::order_status_update_message($request->order_status);
        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type'=>'order_status',
                ];
                if(isset($fcm_token)) {
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }
            }
        } catch (\Exception $e) {
            Toastr::warning(translate('Push notification failed for Customer!'));
        }

        //delivery man notification
        if ($request->order_status == 'processing' && $order->delivery_man != null) {
            $fcm_token = $order->delivery_man->fcm_token;
            $value = translate('One of your order is in processing');
            try {
                if ($value) {
                    $data = [
                        'title' => translate('Order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                        'type'=>'order_status',
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }
            } catch (\Exception $e) {
                Toastr::warning(translate('Push notification failed for DeliveryMan!'));
            }
        }

        Toastr::success(translate('Order status updated!'));
        return back();
    }

    public function preparation_time(Request $request, $id)
    {
        $order = Order::with(['customer'])->find($id);
        $delivery_date_time =  $order['delivery_date']. ' ' .$order['delivery_time'];

        $ordered_time = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($delivery_date_time)));
        $remaining_time = $ordered_time->add($order['preparation_time'], 'minute')->format('Y-m-d H:i:s');

        //if delivery time is not over
        if (strtotime(date('Y-m-d H:i:s')) < strtotime($remaining_time)) {
            $delivery_time = new DateTime($remaining_time); //time when preparation will be over
            $current_time = new DateTime(); // time now
            $interval = $delivery_time->diff($current_time);
            $remainingMinutes = $interval->i;
            $remainingMinutes += $interval->days * 24 * 60;
            $remainingMinutes += $interval->h * 60;

            $order->preparation_time += ($request->extra_minute - $remainingMinutes);

        } else {
            //if delivery time is over
            $delivery_time = new DateTime($remaining_time);
            $current_time = new DateTime();
            $interval = $delivery_time->diff($current_time);
            $diffInMinutes = $interval->i;
            $diffInMinutes += $interval->days * 24 * 60;
            $diffInMinutes += $interval->h * 60;

            $order->preparation_time += $diffInMinutes + $request->extra_minute;
        }
        $order->save();

        //notification send
        $customer = $order->customer;
        $fcm_token = null;
        if (isset($customer)) {
            $fcm_token = $customer->cm_firebase_token;
        }
        $value = Helpers::order_status_update_message('customer_notify_message_for_time_change');

        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type'=>'order_status',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            } else {
                throw new \Exception(translate('failed'));
            }

        } catch (\Exception $e) {
            Toastr::warning(translate('Push notification send failed for Customer!'));
        }

        Toastr::success(translate('Order preparation time increased'));
        return back();
    }

    public function add_delivery_man($order_id, $delivery_man_id)
    {
        if ($delivery_man_id == 0) {
            return response()->json([], 401);
        }
        $order = Order::where(['id' => $order_id, 'branch_id' => auth('branch')->id()])->first();
        $order->delivery_man_id = $delivery_man_id;
        $order->save();

        $fcm_token = $order->delivery_man->fcm_token;
        $value = Helpers::order_status_update_message('del_assign');
        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type'=>'order_status',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch (\Exception $e) {
            Toastr::warning(translate('Push notification failed for DeliveryMan!'));
        }

        Toastr::success(translate('Order deliveryman added!'));
        return response()->json([], 200);
    }

    public function payment_status(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'branch_id' => auth('branch')->id()])->first();
        if ($request->payment_status == 'paid' && $order['transaction_reference'] == null && $order['payment_method'] != 'cash_on_delivery') {
            Toastr::warning(translate('Add your payment reference code first!'));
            return back();
        }
        $order->payment_status = $request->payment_status;
        $order->save();
        Toastr::success(translate('Payment status updated!'));
        return back();
    }

    public function update_shipping(Request $request, $id)
    {
        $request->validate([
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required'
        ]);

        $address = [
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('customer_addresses')->where('id', $id)->update($address);
        Toastr::success(translate('Address updated!'));
        return back();
    }

    public function generate_invoice($id)
    {
        $order = Order::where(['id' => $id, 'branch_id' => auth('branch')->id()])->first();
        return view('branch-views.order.invoice', compact('order'));
    }

    public function add_payment_ref_code(Request $request, $id)
    {
        Order::where(['id' => $id, 'branch_id' => auth('branch')->id()])->update([
            'transaction_reference' => $request['transaction_reference']
        ]);

        Toastr::success(translate('Payment reference code is added!'));
        return back();
    }

}
