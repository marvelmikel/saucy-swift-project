@extends('layouts.admin.app')

@section('title', translate('Update Coupon'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{translate('coupon')}} {{translate('update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.coupon.update',[$coupon['id']])}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                                <input type="text" name="title" value="{{$coupon['title']}}" class="form-control"
                                       placeholder="{{ translate('New coupon') }}" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('coupon')}} {{translate('type')}}</label>
                                <select name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                    <option value="default" {{$coupon['coupon_type']=='default'?'selected':''}}>
                                        {{translate('default')}}
                                    </option>
                                    <option value="first_order" {{$coupon['coupon_type']=='first_order'?'selected':''}}>
                                        {{translate('first')}} {{translate('order')}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4" id="limit-for-user" style="display: {{$coupon['coupon_type']=='first_order'?'none':'block'}}">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('limit')}} {{translate('for')}} {{translate('same')}} {{translate('user')}}</label>
                                <input type="number" name="limit" value="{{$coupon['limit']}}" class="form-control"
                                       placeholder="{{ translate('EX: 10') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('code')}}</label>
                                <input type="text" name="code" class="form-control" value="{{$coupon['code']}}"
                                       placeholder="{{\Illuminate\Support\Str::random(8)}}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="">{{translate('start')}} {{translate('date')}}</label>
                                <input type="text" name="start_date" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('Select dates') }}" value="{{date('Y/m/d',strtotime($coupon['start_date']))}}"
                                       data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="">{{translate('expire')}} {{translate('date')}}</label>
                                <input type="text" name="expire_date" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('Select dates') }}" value="{{date('Y/m/d',strtotime($coupon['expire_date']))}}"
                                       data-hs-flatpickr-options='{
                                     "dateFormat": "Y/m/d",
                                     "minDate": "today"
                                   }'>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('min')}} {{translate('purchase')}}</label>
                                <input type="number" name="min_purchase" step="any" value="{{$coupon['min_purchase']}}"
                                       min="0" max="100000" class="form-control"
                                       placeholder="{{ translate('100') }}">
                            </div>
                        </div>

                        <div class="col-md-3 col-6" id="max_discount_div" style="@if($coupon['discount_type']=='amount') display: none; @endif">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('max')}} {{translate('discount')}}</label>
                                <input type="number" min="0" max="1000000" step="any"
                                       value="{{$coupon['max_discount']}}" name="max_discount" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('discount')}}</label>
                                <input type="number" min="1" max="10000" step="any" value="{{$coupon['discount']}}"
                                       name="discount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('discount')}} {{translate('type')}}</label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option value="percent" {{$coupon['discount_type']=='percent'?'selected':''}}>{{translate('percent')}}</option>
                                    <option value="amount" {{$coupon['discount_type']=='amount'?'selected':''}}>{{translate('amount')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{translate('update')}}</button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $("#discount_type").change(function(){
            if(this.value === 'amount') {
                $("#max_discount_div").hide();
            }
            else if(this.value === 'percent') {
                $("#max_discount_div").show();
            }
        });
    </script>
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });
        });

        function coupon_type_change(order_type) {
            if(order_type=='first_order'){
                $('#limit-for-user').hide();
            }else{
                $('#limit-for-user').show();
            }
        }
    </script>
@endpush
