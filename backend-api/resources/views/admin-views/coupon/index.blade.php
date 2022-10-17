@extends('layouts.admin.app')

@section('title', translate('Add new coupon'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{translate('add')}} {{translate('new')}} {{translate('coupon')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.coupon.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                                <input type="text" name="title" class="form-control" placeholder="{{ translate('New coupon') }}" required maxlength="100">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('coupon')}} {{translate('type')}}</label>
                                <select name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                    <option value="default">{{translate('default')}}</option>
                                    <option value="first_order">{{translate('first order')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4" id="limit-for-user">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('limit')}} {{translate('for')}} {{translate('same')}} {{translate('user')}}</label>
                                <input type="number" name="limit" id="user-limit" class="form-control" placeholder="{{ translate('EX: 10') }}" required min="1">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('code')}}</label>
                                <input type="text" name="code" class="form-control" maxlength="15"
                                       placeholder="{{\Illuminate\Support\Str::random(8)}}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('start')}} {{translate('date')}}</label>
                                <input type="text" name="start_date" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('Select dates') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('expire')}} {{translate('date')}}</label>
                                <input type="text" name="expire_date" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('Select dates') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('min')}} {{translate('purchase')}}</label>
                                <input type="number" step="any" name="min_purchase" value="0" min="0" max="100000" class="form-control"
                                       placeholder="{{ translate('100') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-6" id="max_discount_div">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('max')}} {{translate('discount')}}</label>
                                <input type="number" step="any" min="0" value="0" max="1000000" name="max_discount" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('discount')}}</label>
                                <input type="number" step="any" min="1" max="10000" name="discount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('discount')}} {{translate('type')}}</label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option value="percent">{{translate('percent')}}</option>
                                    <option value="amount">{{translate('amount')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2 mt-2">
                <div class="card">
                    <div class="card-header flex-between">
                        <div class="flex-start">
                            <h5 class="card-header-title">{{translate('Coupon Table')}}</h5>
                            <h5 class="card-header-title text-primary mx-1">({{ $coupons->total() }})</h5>
                        </div>
                        <div>
                            <form action="{{url()->current()}}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                           class="form-control"
                                           placeholder="{{translate('Search')}}" aria-label="Search"
                                           value="{{$search}}" required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text"><i class="tio-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th>{{translate('title')}}</th>
                                <th>{{translate('code')}}</th>
                                <th>{{translate('min')}} {{translate('purchase')}}</th>
                                <th>{{translate('max')}} {{translate('discount')}}</th>
                                <th>{{translate('discount')}}</th>
                                <th>{{translate('discount')}} {{translate('type')}}</th>
                                <th>{{translate('start')}} {{translate('date')}}</th>
                                <th>{{translate('expire')}} {{translate('date')}}</th>
                                <th>{{translate('status')}}</th>
                                <th>{{translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($coupons as $key=>$coupon)
                                <tr>
                                    <td>{{$coupons->firstItem()+$key}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{$coupon['title']}}
                                    </span>
                                    </td>
                                    <td>{{$coupon['code']}}</td>
                                    <td>{{ \App\CentralLogics\Helpers::set_symbol($coupon['min_purchase']) }}</td>
                                    <td>{{ \App\CentralLogics\Helpers::set_symbol($coupon['max_discount']) }}</td>
                                    <td>{{$coupon['discount']}}</td>
                                    <td>{{$coupon['discount_type']}}</td>
                                    <td>{{date('d-m-Y', strtotime($coupon['start_date']))}}</td>
                                    <td>{{date('d-m-Y', strtotime($coupon['expire_date']))}}</td>
                                    <td>
                                        @if($coupon['status']==1)
                                            <div style="padding: 10px;border: 1px solid;cursor: pointer"
                                                 onclick="location.href='{{route('admin.coupon.status',[$coupon['id'],0])}}'">
                                                <span class="legend-indicator bg-success"></span>{{translate('active')}}
                                            </div>
                                        @else
                                            <div style="padding: 10px;border: 1px solid;cursor: pointer"
                                                 onclick="location.href='{{route('admin.coupon.status',[$coupon['id'],1])}}'">
                                                <span class="legend-indicator bg-danger"></span>{{translate('disabled')}}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                   href="{{route('admin.coupon.update',[$coupon['id']])}}">{{translate('edit')}}</a>
                                                <a class="dropdown-item" href="javascript:"
                                                   onclick="form_alert('coupon-{{$coupon['id']}}','{{translate('Want to delete this coupon ?')}}')">{{translate('delete')}}</a>
                                                <form action="{{route('admin.coupon.delete',[$coupon['id']])}}"
                                                      method="post" id="coupon-{{$coupon['id']}}">
                                                    @csrf @method('delete')
                                                </form>
                                            </div>
                                        </div>
                                        <!-- End Dropdown -->
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <table>
                            <tfoot>
                            {!! $coupons->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
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
                $('#user-limit').removeAttr('required');
                $('#limit-for-user').hide();
            }else{
                $('#user-limit').prop('required',true);
                $('#limit-for-user').show();
            }
        }
    </script>
@endpush
nput type="text" name="start_date" class
