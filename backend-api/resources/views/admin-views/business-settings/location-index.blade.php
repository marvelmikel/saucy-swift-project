@extends('layouts.admin.app')

@section('title', translate('Settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
    @php($branch_count=\App\Model\Branch::count())
    <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('location coverage setup')}}</h1>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12" style="overflow-x: scroll;">
                                <span class="badge badge-soft-danger" style="text-align: left">
                                    {{translate('This location setup is for your Main branch. Carefully set your restaurant location and coverage area. If you want to ignore the coverage area then keep the input box empty.')}}
                                    <br>
                                    {{translate('You can ignore this when you have only the default branch and you do not want coverage area.')}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.restaurant.update-location'):'javascript:'}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @php($data=\App\Model\Branch::find(1))
                    <div class="row">
                        {{--<div class="col-md-12 col-12">
                            <label class="toggle-switch d-flex align-items-center mb-3" for="customSwitch1">
                                <input type="checkbox" name="status" class="toggle-switch-input" value="1" id="customSwitch1" {{$data['status']==1?'checked':''}}>
                                <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                              </span>
                                <span class="toggle-switch-content">
                                <span class="d-block">Status</span>
                              </span>
                            </label>
                        </div>--}}
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('latitude')}}</label>
                                <input type="text" value="{{$data['latitude']}}"
                                       name="latitude" class="form-control"
                                       placeholder="{{translate('Ex : -94.22213')}}" {{$branch_count>1?'required':''}}>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('longitude')}}</label>
                                <input type="text" value="{{$data['longitude']}}"
                                       name="longitude" class="form-control"
                                       placeholder="{{translate('Ex : 103.344322')}}" {{$branch_count>1?'required':''}}>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="">
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{translate('This value is the radius from your restaurant location, and customer can order food inside  the circle calculated by this radius.')}}"></i>
                                    {{translate('coverage')}} ( {{translate('km')}} )
                                </label>
                                <input type="number" value="{{$data['coverage']}}"
                                       name="coverage" class="form-control" placeholder="{{translate('Ex : 3')}}" {{$branch_count>1?'required':''}}>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{translate('update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
