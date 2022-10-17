@extends('layouts.admin.app')

@section('title','')

@push('css_or_js')
    <style>
        @media print {
            .non-printable {
                display: none;
            }

            .printable {
                display: block;
                font-family: emoji !important;
            }

            body {
                -webkit-print-color-adjust: exact !important; /* Chrome, Safari */
                color-adjust: exact !important;
                font-family: emoji !important;
            }
        }

        .hr-style-2 {
            border: 0;
            height: 1px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
        }

        .hr-style-1 {
            overflow: visible;
            padding: 0;
            border: none;
            border-top: medium double #333;
            color: #333;
            text-align: center;
        }
    </style>

    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 2px;  /* this affects the margin in the printer settings */
            font-family: emoji !important;
        }

    </style>
@endpush

@section('content')

    <div class="content container-fluid" style="color: black">
        <div class="row" id="printableArea" style="font-family: emoji;">
            <div class="col-md-12">
                <center>
                    <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                           value="{{translate('Proceed, If thermal printer is ready.')}}"/>
                    <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{translate('Back')}}</a>
                </center>
                <hr class="non-printable">
            </div>
            <div class="col-5">
                <div class="text-center pt-4 mb-3">
                    <h2 style="line-height: 1">{{\App\Model\BusinessSetting::where(['key'=>'restaurant_name'])->first()->value}}</h2>
                    <h5 style="font-size: 20px;font-weight: lighter;line-height: 1">
                        {{\App\Model\BusinessSetting::where(['key'=>'address'])->first()->value}}
                    </h5>
                    <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                        Phone : {{\App\Model\BusinessSetting::where(['key'=>'phone'])->first()->value}}
                    </h5>
                </div>

                <hr class="text-dark hr-style-1">

                <div class="row mt-4">
                    <div class="col-6">
                        <h5>{{translate('Order ID : ')}}{{$order['id']}}</h5>
                    </div>
                    <div class="col-6">
                        <h5 style="font-weight: lighter">
                            <span class="font-weight-normal">{{date('d/M/Y h:m a',strtotime($order['created_at']))}}</span>
                        </h5>
                    </div>
                    <div class="col-12">
                        @if(isset($order->customer))
                            <h5>
                                {{translate('Customer Name : ')}}<span class="font-weight-normal">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</span>
                            </h5>
                            <h5>
                                {{translate('Phone : ')}}<span class="font-weight-normal">{{$order->customer['phone']}}</span>
                            </h5>
                            @php($address=\App\Model\CustomerAddress::find($order['delivery_address_id']))
                            <h5>
                                {{translate('Address : ')}}<span class="font-weight-normal">{{isset($address)?$address['address']:''}}</span>
                            </h5>
                        @endif
                    </div>
                </div>
                <h5 class="text-uppercase"></h5>
                <hr class="text-dark hr-style-2">
                <table class="table table-bordered mt-3" style="width: 98%;color: black!important;">
                    <thead>
                    <tr>
                        <th style="width: 10%">{{translate('QTY')}}</th>
                        <th class="">{{translate('DESC')}}</th>
                        <th class="">{{translate('Price')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @php($sub_total=0)
                    @php($total_tax=0)
                    @php($total_dis_on_pro=0)
                    @php($add_ons_cost=0)
                    @foreach($order->details as $detail)
                        @if($detail->product)
                            @php($add_on_qtys=json_decode($detail['add_on_qtys'],true))
                            <tr>
                                <td class="">
                                    {{$detail['quantity']}}
                                </td>
                                <td class="">
                                    {{$detail->product['name']}} <br>
                                    @if(count(json_decode($detail['variation'],true))>0)
                                        <strong><u>{{translate('Variation : ')}}</u></strong>
                                        @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)
                                            <div class="font-size-sm text-body" style="color: black!important;">
                                                <span>{{$key1}} :  </span>
                                                <span class="font-weight-bold">{{ $key1 == 'price' ?  Helpers::set_symbol($variation) : $variation }}</span>
                                            </div>
                                        @endforeach
                                    @endif

                                    @foreach(json_decode($detail['add_on_ids'],true) as $key2 =>$id)
                                        @php($addon=\App\Model\AddOn::find($id))
                                        @if($key2==0)<strong><u>{{translate('VAddons : ')}}</u></strong>@endif

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

                                    {{translate('Discount : ')}}{{ \App\CentralLogics\Helpers::set_symbol($detail['discount_on_product']) }}
                                </td>
                                <td style="width: 28%">
                                    @php($amount=($detail['price']-$detail['discount_on_product'])*$detail['quantity'])
                                    {{ \App\CentralLogics\Helpers::set_symbol($amount) }}
                                </td>
                            </tr>
                            @php($sub_total+=$amount)
                            @php($total_tax+=$detail['tax_amount']*$detail['quantity'])
                        @endif
                    @endforeach
                    </tbody>
                </table>


                <div class="row justify-content-md-end mb-3" style="width: 97%">
                    <div class="col-md-7 col-lg-7">
                        <dl class="row text-right" style="color: black!important;">
                            <dt class="col-6">{{translate('Items Price:')}}</dt>
                            <dd class="col-6">{{ \App\CentralLogics\Helpers::set_symbol($sub_total) }}</dd>
                            <dt class="col-6">{{translate('Tax / VAT:')}}</dt>
                            <dd class="col-6">{{ \App\CentralLogics\Helpers::set_symbol($total_tax) }}</dd>
                            <dt class="col-6">{{translate('Addon Cost:')}}</dt>
                            <dd class="col-6">
                                {{ \App\CentralLogics\Helpers::set_symbol($add_ons_cost) }}
                                <hr>
                            </dd>

                            <dt class="col-6">{{translate('Subtotal:')}}</dt>
                            <dd class="col-6">
                                {{ \App\CentralLogics\Helpers::set_symbol($sub_total+$total_tax+$add_ons_cost) }}</dd>
                            <dt class="col-6">{{translate('Extra Discount')}}:</dt>
                            <dd class="col-6">
                                - {{ \App\CentralLogics\Helpers::set_symbol($order['extra_discount']) }}</dd>
                            <dt class="col-6">{{translate('Coupon Discount:')}}</dt>
                            <dd class="col-6">
                                - {{ \App\CentralLogics\Helpers::set_symbol($order['coupon_discount_amount']) }}</dd>
                            <dt class="col-6">{{translate('Delivery Fee:')}}</dt>
                            <dd class="col-6">
                                @if($order['order_type']=='take_away')
                                    @php($del_c=0)
                                @else
                                    @php($del_c=$order['delivery_charge'])
                                @endif
                                {{ \App\CentralLogics\Helpers::set_symbol($del_c) }}
                                <hr>
                            </dd>

                            <dt class="col-6" style="font-size: 20px">{{translate('Total:')}}</dt>
                            <dd class="col-6" style="font-size: 20px">{{ \App\CentralLogics\Helpers::set_symbol($sub_total+$del_c+$total_tax+$add_ons_cost-$order['coupon_discount_amount']-$order['extra_discount']) }}</dd>
                        </dl>
                    </div>
                </div>
                <hr class="text-dark hr-style-2">
                <h5 class="text-center pt-3">
                    {{translate('"""THANK YOU"""')}}
                </h5>
                <hr class="text-dark hr-style-2">
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
