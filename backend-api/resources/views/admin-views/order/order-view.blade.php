@extends('layouts.admin.app')

@section('title', translate('Order Details'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link"
                                   href="{{route('admin.orders.list',['status'=>'all'])}}">
                                   {{translate('Orders')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active"
                                aria-current="page">{{translate('order')}} {{translate('details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{translate('order')}} #{{$order['id']}}</h1>

                        @if($order['payment_status']=='paid')
                            <span class="badge badge-soft-success ml-sm-3">
                                <span class="legend-indicator bg-success"></span>{{translate('paid')}}
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-sm-3">
                                <span class="legend-indicator bg-danger"></span>{{translate('unpaid')}}
                            </span>
                        @endif

                        @if($order['order_status']=='pending')
                            <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-info text"></span>{{translate('pending')}}
                            </span>
                        @elseif($order['order_status']=='confirmed')
                            <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-info"></span>{{translate('confirmed')}}
                            </span>
                        @elseif($order['order_status']=='processing')
                            <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-warning"></span>{{translate('processing')}}
                            </span>
                        @elseif($order['order_status']=='out_for_delivery')
                            <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-warning"></span>{{translate('out_for_delivery')}}
                            </span>
                        @elseif($order['order_status']=='delivered')
                            <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-success"></span>{{translate('delivered')}}
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-danger"></span>{{str_replace('_',' ',$order['order_status'])}}
                            </span>
                        @endif
                        <span class="ml-2 ml-sm-3">
                           <i class="tio-date-range"></i> {{date('d M Y',strtotime($order['created_at']))}} {{ date(config('time_format'), strtotime($order['created_at'])) }}
                        </span>
                        @if($order['delivery_date'] > \Carbon\Carbon::now()->format('Y-m-d'))
                            <span class="ml-2 ml-sm-3 badge badge-soft-success">
                               <i class="tio-time"></i> {{translate('scheduled')}} : {{date('d-M-Y',strtotime($order['delivery_date']))}} {{ date(config('time_format'), strtotime($order['delivery_time'])) }}
                            </span>
                        @else
                            <span class="ml-2 ml-sm-3 badge badge-soft-success">
                               <i class="tio-time"></i> {{date('d-M-Y',strtotime($order['delivery_date']))}} {{ date(config('time_format'), strtotime($order['delivery_time'])) }}
                            </span>
                        @endif

                        {{-- counter --}}
                        @if($order['order_type'] != 'pos' && $order['order_type'] != 'take_away' && ($order['order_status'] != DELIVERED && $order['order_status'] != RETURNED && $order['order_status'] != CANCELED && $order['order_status'] != FAILED))
                            <span class="ml-2 ml-sm-3 ">
                                <i class="tio-timer d-none" id="timer-icon"></i>
                                <span id="counter" class="text-info"></span>
                                <i class="tio-edit p-2 d-none" id="edit-icon" style="cursor: pointer;" data-toggle="modal" data-target="#counter-change" data-whatever="@mdo"></i>
                            </span>
                        @endif
                    </div>

                    <div class="mt-2">
                        <a class="text-body mr-3"
                           href={{route('admin.orders.generate-invoice',[$order['id']])}}>
                            <i class="tio-print mr-1"></i> {{translate('print')}} {{translate('invoice')}}
                        </a>

                        <!-- Unfold -->
                        @if($order['order_type']!='take_away' && $order['order_type'] != 'pos')
                            <div class="hs-unfold">
                                <select class="form-control" name="delivery_man_id"
                                        onchange="addDeliveryMan(this.value)">
                                    <option
                                        value="0">{{translate('Select Delivery Man')}}</option>
                                    @foreach(\App\Model\DeliveryMan::all() as $deliveryMan)
                                        <option
                                            value="{{$deliveryMan['id']}}" {{$order['delivery_man_id']==$deliveryMan['id']?'selected':''}}>
                                            {{$deliveryMan['f_name'].' '.$deliveryMan['l_name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="hs-unfold ml-1">
                                @if($order['order_status']=='out_for_delivery')
                                    @php($origin=\App\Model\DeliveryHistory::where(['deliveryman_id'=>$order['delivery_man_id'],'order_id'=>$order['id']])->first())
                                    @php($current=\App\Model\DeliveryHistory::where(['deliveryman_id'=>$order['delivery_man_id'],'order_id'=>$order['id']])->latest()->first())
                                    @if(isset($origin))
                                        {{--<a class="btn btn-outline-primary" target="_blank"
                                           title="Delivery Boy Last Location" data-toggle="tooltip" data-placement="top"
                                           href="http://maps.google.com/maps?z=12&t=m&q=loc:{{$location['latitude']}}+{{$location['longitude']}}">
                                            <i class="tio-map"></i>
                                        </a>--}}
                                        <a class="btn btn-outline-primary" target="_blank"
                                           title="{{translate('Delivery Boy Last Location')}}" data-toggle="tooltip" data-placement="top"
                                           href="https://www.google.com/maps/dir/?api=1&origin={{$origin['latitude']}},{{$origin['longitude']}}&destination={{$current['latitude']}},{{$current['longitude']}}">
                                            <i class="tio-map"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-outline-primary" href="javascript:" data-toggle="tooltip"
                                           data-placement="top" title="{{translate('Waiting for location...')}}">
                                            <i class="tio-map"></i>
                                        </a>
                                    @endif
                                @else
                                    <a class="btn btn-outline-dark" href="javascript:" onclick="last_location_view()"
                                       data-toggle="tooltip" data-placement="top"
                                       title="{{translate('Only available when order is out for delivery!')}}">
                                        <i class="tio-map"></i>
                                    </a>
                                @endif
                            </div>
                        @endif

                        <div class="hs-unfold ml-1">
                            <h5 class="text-capitalize">
                                <i class="tio-shop"></i>
                                {{translate('branch')}} : <label
                                    class="badge badge-secondary">{{$order->branch?$order->branch->name:'Branch deleted!'}}</label>
                            </h5>
                        </div>

                        <div class="hs-unfold float-right">
                            @if($order['order_type'] != 'pos')
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        {{translate('status')}}
                                    </button>
                                    <div class="dropdown-menu text-capitalize dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.status',['id'=>$order['id'],'order_status'=>'pending'])}}','{{\App\CentralLogics\translate("Change status to pending ?")}}')"
                                           href="javascript:">{{translate('pending')}}</a>
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.status',['id'=>$order['id'],'order_status'=>'confirmed'])}}','{{\App\CentralLogics\translate("Change status to confirmed ?")}}')"
                                           href="javascript:">{{translate('confirmed')}}</a>
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.status',['id'=>$order['id'],'order_status'=>'processing'])}}','{{\App\CentralLogics\translate("Change status to processing ?")}}')"
                                           href="javascript:">{{translate('processing')}}</a>
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.status',['id'=>$order['id'],'order_status'=>'out_for_delivery'])}}','{{\App\CentralLogics\translate("Change status to out for delivery ?")}}')"
                                           href="javascript:">{{translate('out_for_delivery')}}</a>
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.status',['id'=>$order['id'],'order_status'=>'delivered'])}}','{{\App\CentralLogics\translate("Change status to delivered ?")}}')"
                                           href="javascript:">{{translate('delivered')}}</a>
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.status',['id'=>$order['id'],'order_status'=>'returned'])}}','{{\App\CentralLogics\translate("Change status to returned ?")}}')"
                                           href="javascript:">{{translate('returned')}}</a>
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.status',['id'=>$order['id'],'order_status'=>'failed'])}}','{{\App\CentralLogics\translate("Change status to failed ?")}}')"
                                           href="javascript:">{{translate('failed')}}</a>
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.status',['id'=>$order['id'],'order_status'=>'canceled'])}}','{{\App\CentralLogics\translate("Change status to canceled ?")}}')"
                                           href="javascript:">{{translate('canceled')}}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="hs-unfold float-right pr-2">
                            @if($order['order_type'] != 'pos')
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        {{translate('payment')}}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.payment-status',['id'=>$order['id'],'payment_status'=>'paid'])}}','{{\App\CentralLogics\translate("Change status to paid ?")}}')"
                                           href="javascript:">{{translate('paid')}}</a>
                                        <a class="dropdown-item"
                                           onclick="route_alert('{{route('admin.orders.payment-status',['id'=>$order['id'],'payment_status'=>'unpaid'])}}','{{\App\CentralLogics\translate("Change status to unpaid ?")}}')"
                                           href="javascript:">{{translate('unpaid')}}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- End Unfold -->
                    </div>
                </div>

                <div class="col-sm-auto">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle mr-1"
                       href="{{route('admin.orders.details',[$order['id']-1])}}"
                       data-toggle="tooltip" data-placement="top" title="{{translate('Previous order')}}">
                        <i class="tio-arrow-backward"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle"
                       href="{{route('admin.orders.details',[$order['id']+1])}}" data-toggle="tooltip"
                       data-placement="top" title="{{translate('Next order')}}">
                        <i class="tio-arrow-forward"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-{{$order->customer!=null ? 8 : 12}} mb-3 mb-lg-0">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header" style="display: block!important;">
                        <div class="row">
                            <div class="col-12 pb-2 border-bottom">
                                <h4 class="card-header-title">
                                    {{translate('order')}} {{translate('details')}}
                                    <span
                                        class="badge badge-soft-dark rounded-circle ml-1">{{$order->details->count()}}</span>
                                </h4>
                            </div>
                            <div class="col-6 pt-2">
                                <h6 style="color: #8a8a8a;">
                                    {{translate('order')}} {{translate('note')}} : {{$order['order_note']}}
                                </h6>
                            </div>
                            <div class="col-6 pt-2">
                                <div class="text-right">
                                    <h6 class="text-capitalize" style="color: #8a8a8a;">
                                        {{translate('payment')}} {{translate('method')}}
                                        : {{str_replace('_',' ',$order['payment_method'])}}
                                    </h6>
                                    <h6 class="" style="color: #8a8a8a;">
                                        @if($order['transaction_reference']==null && $order['order_type']!='pos')
                                            {{translate('reference')}} {{translate('code')}} :
                                            <button class="btn btn-outline-primary btn-sm" data-toggle="modal"
                                                    data-target=".bd-example-modal-sm">
                                                {{translate('add')}}
                                            </button>
                                        @elseif($order['order_type']!='pos')
                                            {{translate('reference')}} {{translate('code')}}
                                            : {{$order['transaction_reference']}}
                                        @endif
                                    </h6>
                                    <h6 class="text-capitalize"
                                        style="color: #8a8a8a;">{{translate('order')}} {{translate('type')}}
                                        : <label style="font-size: 10px"
                                                 class="badge badge-soft-primary">{{str_replace('_',' ',$order['order_type'])}}</label>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                    @php($sub_total=0)
                    @php($total_tax=0)
                    @php($total_dis_on_pro=0)
                    @php($add_ons_cost=0)
                    @foreach($order->details as $detail)
                        @if($detail->product)
                            @php($add_on_qtys=json_decode($detail['add_on_qtys'],true))
                            <!-- Media -->
                                <div class="media">
                                    <div class="avatar avatar-xl mr-3">
                                        <img class="img-fluid"
                                             src="{{asset('storage/app/public/product')}}/{{$detail->product['image']}}"
                                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                             alt="Image Description">
                                    </div>

                                    <div class="media-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <strong> {{$detail->product['name']}}</strong><br>

                                                @if(count(json_decode($detail['variation'],true))>0)
                                                    <strong><u>{{translate('variation')}} : </u></strong>
                                                    @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)
                                                        <div class="font-size-sm text-body">
                                                            <span>{{$key1}} :  </span>
                                                            <span class="font-weight-bold">{{ $key1 == 'price' ?  Helpers::set_symbol($variation) : $variation }}</span>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                @foreach(json_decode($detail['add_on_ids'],true) as $key2 =>$id)
                                                    @php($addon=\App\Model\AddOn::find($id))
                                                    @if($key2==0)<strong><u>{{translate('addons')}}
                                                            : </u></strong>@endif

                                                    @if($add_on_qtys==null)
                                                        @php($add_on_qty=1)
                                                    @else
                                                        @php($add_on_qty=$add_on_qtys[$key2])
                                                    @endif

                                                    <div class="font-size-sm text-body">
                                                        <span>{{$addon['name']}} :  </span>
                                                        <span class="font-weight-bold">
                                                            {{$add_on_qty}} x {{ \App\CentralLogics\Helpers::set_symbol($addon['price']) }}
                                                        </span>
                                                    </div>
                                                    @php($add_ons_cost+=$addon['price']*$add_on_qty)
                                                @endforeach
                                            </div>

                                            <div class="col col-md-2 align-self-center">
                                                @if($detail['discount_on_product']!=0)
                                                    <h5>
                                                        <strike>
{{--                                                            {{\App\CentralLogics\Helpers::variation_price(json_decode($detail['product_details'],true),$detail['variation']) ." ".\App\CentralLogics\Helpers::currency_symbol()}}--}}
                                                        </strike>
                                                    </h5>
                                                @endif
                                                <h6>{{ \App\CentralLogics\Helpers::set_symbol($detail['price']-$detail['discount_on_product']) }}</h6>
                                            </div>
                                            <div class="col col-md-1 align-self-center">
                                                <h5>{{$detail['quantity']}}</h5>
                                            </div>

                                            <div class="col col-md-3 align-self-center text-right">
                                                @php($amount=($detail['price']-$detail['discount_on_product'])*$detail['quantity'])
                                                <h5>{{ \App\CentralLogics\Helpers::set_symbol($amount) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @php($sub_total+=$amount)
                            @php($total_tax+=$detail['tax_amount']*$detail['quantity'])
                            <!-- End Media -->
                                <hr>
                            @endif
                        @endforeach

                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-sm-right">
                                    <dt class="col-sm-6">{{translate('items')}} {{translate('price')}}:</dt>
                                    <dd class="col-sm-6">{{ \App\CentralLogics\Helpers::set_symbol($sub_total) }}</dd>
                                    <dt class="col-sm-6">{{translate('tax')}} / {{translate('vat')}}:</dt>
                                    <dd class="col-sm-6">{{ \App\CentralLogics\Helpers::set_symbol($total_tax) }}</dd>
                                    <dt class="col-sm-6">{{translate('addon')}} {{translate('cost')}}:</dt>
                                    <dd class="col-sm-6">
                                        {{ \App\CentralLogics\Helpers::set_symbol($add_ons_cost) }}
                                        <hr>
                                    </dd>

                                    <dt class="col-sm-6">{{translate('subtotal')}}:</dt>
                                    <dd class="col-sm-6">
                                        {{ \App\CentralLogics\Helpers::set_symbol($sub_total+$total_tax+$add_ons_cost) }}</dd>
                                    <dt class="col-sm-6">{{translate('coupon')}} {{translate('discount')}}: </dt>
                                    <dd class="col-sm-6">
                                        - {{ \App\CentralLogics\Helpers::set_symbol($order['coupon_discount_amount']) }}</dd>
                                    <dt class="col-sm-6">{{translate('extra discount')}} :</dt>
                                    <dd class="col-sm-6">
                                        - {{ \App\CentralLogics\Helpers::set_symbol($order['extra_discount']) }}</dd>
                                    <dt class="col-sm-6">{{translate('delivery')}} {{translate('fee')}}:</dt>
                                    <dd class="col-sm-6">
                                        @if($order['order_type']=='take_away')
                                            @php($del_c=0)
                                        @else
                                            @php($del_c=$order['delivery_charge'])
                                        @endif
                                        {{ \App\CentralLogics\Helpers::set_symbol($del_c) }}
                                        <hr>
                                    </dd>

                                    <dt class="col-sm-6">{{translate('total')}}:</dt>
                                    <dd class="col-sm-6">{{ \App\CentralLogics\Helpers::set_symbol($sub_total+$del_c+$total_tax+$add_ons_cost-$order['coupon_discount_amount']-$order['extra_discount']) }}</dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

            @if($order->customer)
                <div class="col-lg-4">
                    <!-- Card -->
                    <div class="card">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="card-header-title">{{translate('customer')}}</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                            <div class="card-body">
                                <div class="media align-items-center" href="javascript:">
                                    <div class="avatar avatar-circle mr-3">
                                        <a href="{{route('admin.customer.view',[$order->customer['id']])}}">
                                            <img
                                                class="avatar-img" style="width: 75px"
                                                onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                src="{{asset('storage/app/public/profile/'.$order->customer->image)}}"
                                                alt="Image Description">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <span class="text-body text-hover-primary">
                                            <a href="{{route('admin.customer.view',[$order->customer['id']])}}">
                                                {{$order->customer['f_name']." ".$order->customer['l_name']}}
                                            </a>
                                        </span>
                                    </div>
                                    <div class="media-body text-right">
                                        {{--<i class="tio-chevron-right text-body"></i>--}}
                                    </div>
                                </div>

                                <hr>

                                <div class="media align-items-center" href="javascript:">
                                    <div class="icon icon-soft-info icon-circle mr-3">
                                        <i class="tio-shopping-basket-outlined"></i>
                                    </div>
                                    <div class="media-body">
                                        <span class="text-body text-hover-primary">{{\App\Model\Order::where('user_id',$order['user_id'])->count()}} {{translate('orders')}}</span>
                                    </div>
                                    <div class="media-body text-right">
                                        {{--<i class="tio-chevron-right text-body"></i>--}}
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>{{translate('contact')}} {{translate('info')}}</h5>
                                </div>

                                <ul class="list-unstyled list-unstyled-py-2">
                                    <li>
                                        <i class="tio-online mr-2"></i>
                                        {{$order->customer['email']}}
                                    </li>
                                    <li>
                                        <i class="tio-android-phone-vs mr-2"></i>
                                        {{$order->customer['phone']}}
                                    </li>
                                </ul>

                                @if($order['order_type']!='take_away')
                                    <hr>
                                    @php($address=\App\Model\CustomerAddress::find($order['delivery_address_id']))
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>{{translate('delivery')}} {{translate('address')}}</h5>
                                        @if(isset($address))
                                            <a class="link" data-toggle="modal" data-target="#shipping-address-modal"
                                               href="javascript:">{{translate('edit')}}</a>
                                        @endif
                                    </div>
                                    @if(isset($address))
                                        <span class="d-block">
                                        {{$address['contact_person_name']}}<br>
                                        {{$address['contact_person_number']}}<br>
                                        {{$address['address_type']}}<br>
                                        <a target="_blank"
                                           href="http://maps.google.com/maps?z=12&t=m&q=loc:{{$address['latitude']}}+{{$address['longitude']}}">
                                           <i class="tio-map"></i> {{$address['address']}}<br>
                                        </a>
                                    </span>
                                    @endif
                                @endif
                            </div>
                    <!-- End Body -->
                    </div>
                    <!-- End Card -->
                </div>
            @endif
        </div>
        <!-- End Row -->
    </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4"
                        id="mySmallModalLabel">{{translate('reference')}} {{translate('code')}} {{translate('add')}}</h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal"
                            aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>

                <form action="{{route('admin.orders.add-payment-ref-code',[$order['id']])}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <!-- Input Group -->
                        <div class="form-group">
                            <input type="text" name="transaction_reference" class="form-control"
                                   placeholder="{{translate('EX : Code123')}}" required>
                        </div>
                        <!-- End Input Group -->
                        <button class="btn btn-primary">{{translate('submit')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!-- Modal -->
    <div id="shipping-address-modal" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalTopCoverTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-top-cover bg-dark text-center">
                    <figure class="position-absolute right-0 bottom-0 left-0" style="margin-bottom: -1px;">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                             viewBox="0 0 1920 100.1">
                            <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"/>
                        </svg>
                    </figure>

                    <div class="modal-close">
                        <button type="button" class="btn btn-icon btn-sm btn-ghost-light" data-dismiss="modal"
                                aria-label="Close">
                            <svg width="16" height="16" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor"
                                      d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- End Header -->

                <div class="modal-top-cover-icon">
                    <span class="icon icon-lg icon-light icon-circle icon-centered shadow-soft">
                      <i class="tio-location-search"></i>
                    </span>
                </div>

                @php($address=\App\Model\CustomerAddress::find($order['delivery_address_id']))
                @if(isset($address))
                    <form action="{{route('admin.orders.update-shipping',[$order['delivery_address_id']])}}"
                          method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('type')}}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address_type"
                                           value="{{$address['address_type']}}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('contact')}}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_number"
                                           value="{{$address['contact_person_number']}}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('name')}}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_name"
                                           value="{{$address['contact_person_name']}}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('address')}}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address"
                                           value="{{$address['address']}}"
                                           required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('latitude')}}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="latitude"
                                           value="{{$address['latitude']}}"
                                           required>
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('longitude')}}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="longitude"
                                           value="{{$address['longitude']}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white"
                                    data-dismiss="modal">{{translate('close')}}</button>
                            <button type="submit"
                                    class="btn btn-primary">{{translate('save')}} {{translate('changes')}}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!-- Modal -->
    @if($order['order_type'] != 'pos' && $order['order_type'] != 'take_away' && ($order['order_status'] != DELIVERED && $order['order_status'] != RETURNED && $order['order_status'] != CANCELED && $order['order_status'] != FAILED))
        <div class="modal fade" id="counter-change" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" style="font-size: 20px">{{ translate('Need time to prepare the food') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('admin.orders.increase-preparation-time', ['id' => $order->id])}}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group text-center">
                                <input type="number" min="0" name="extra_minute" id="extra_minute" class="form-control" placeholder="{{translate('EX : 20')}}" required>
                            </div>

                            <div class="form-group flex-between">
                                <div class="badge text-info shadow" onclick="predefined_time_input(10)" style="cursor: pointer">{{ translate('10min') }}</div>
                                <div class="badge text-info shadow" onclick="predefined_time_input(20)" style="cursor: pointer">{{ translate('20min') }}</div>
                                <div class="badge text-info shadow" onclick="predefined_time_input(30)" style="cursor: pointer">{{ translate('30min') }}</div>
                                <div class="badge text-info shadow" onclick="predefined_time_input(40)" style="cursor: pointer">{{ translate('40min') }}</div>
                                <div class="badge text-info shadow" onclick="predefined_time_input(50)" style="cursor: pointer">{{ translate('50min') }}</div>
                                <div class="badge text-info shadow" onclick="predefined_time_input(60)" style="cursor: pointer">{{ translate('60min') }}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <!-- End Modal -->
@endsection

@push('script_2')
    <script>
        function addDeliveryMan(id) {
            $.ajax({
                type: "GET",
                url: '{{url('/')}}/admin/orders/add-delivery-man/{{$order['id']}}/' + id,
                data: $('#product_form').serialize(),
                success: function (data) {
                    if(data.status == true) {
                        toastr.success('{{\App\CentralLogics\translate("Delivery man successfully assigned/changed")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else{
                        toastr.error('{{\App\CentralLogics\translate("Deliveryman man can not assign/change in that status")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function () {
                    toastr.error('{{\App\CentralLogics\translate("Add valid data")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function last_location_view() {
            toastr.warning('{{\App\CentralLogics\translate("Only available when order is out for delivery!")}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>

    <script>
        function predefined_time_input(min) {
            document.getElementById("extra_minute").value = min;
        }
    </script>
    @if($order['order_type'] != 'pos' && $order['order_type'] != 'take_away' && ($order['order_status'] != DELIVERED && $order['order_status'] != RETURNED && $order['order_status'] != CANCELED && $order['order_status'] != FAILED))
        <script>
            const expire_time = "{{ $order['remaining_time'] }}";
            var countDownDate = new Date(expire_time).getTime();
            const time_zone = "{{ \App\CentralLogics\Helpers::get_business_settings('time_zone') ?? 'UTC' }}";

            var x = setInterval(function() {
                var now = new Date(new Date().toLocaleString("en-US", {timeZone: time_zone})).getTime();

                var distance = countDownDate - now;

                var days = Math.trunc(distance / (1000 * 60 * 60 * 24));
                var hours = Math.trunc((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.trunc((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.trunc((distance % (1000 * 60)) / 1000);


                document.getElementById("timer-icon").classList.remove("d-none");
                document.getElementById("edit-icon").classList.remove("d-none");
                $text = (distance < 0) ? "{{ translate('over') }}" : "{{ translate('left') }}";
                document.getElementById("counter").innerHTML = Math.abs(days) + "d " + Math.abs(hours) + "h " + Math.abs(minutes) + "m " + Math.abs(seconds) + "s " + $text;
                if (distance < 0) {
                    var element = document.getElementById('counter');
                    element.classList.add('text-danger');
                }
            }, 1000);
        </script>
    @endif

@endpush
