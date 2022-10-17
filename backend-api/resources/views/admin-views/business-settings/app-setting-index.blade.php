@extends('layouts.admin.app')

@section('title', translate('Settings'))

@push('css_or_js')
    <style>

        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 23px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 15px;
            width: 15px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #FC6A57;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #FC6A57;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('App Settings')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" style="padding-bottom: 20px">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h2 class="text-center">{{translate('Android')}}</h2>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('play_store_config'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.system-setup.app_setting',['platform' => 'android']):'javascript:'}}"
                            method="post">
                            @csrf
                            <div class="form-group mt-4">
                                <div class="my-2">
                                    <label
                                        class="text-dark font-weight-bold">{{ translate('Enable download link for web footer') }}</label>
                                    <label class="switch ml-3 ">
                                        <input type="checkbox" class="status" name="play_store_status"
                                               value="1" {{(isset($config) && $config['status']==1)?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <div class="my-2">
                                    <label class="text-dark"
                                           for="app_store_link">{{ translate('Download link') }}
                                    </label>
                                    <input type="text" id="play_store_link" name="play_store_link"
                                           value="{{$config['link']??''}}" class="form-control" placeholder="">
                                </div>

                                <div class="my-2">
                                    <label class="text-dark"
                                           for="android_min_version">{{ translate('Minimum version for force update') }}
                                        <i class="tio-info text-danger" data-toggle="tooltip" data-placement="right"
                                           title="{{ \App\CentralLogics\translate("If there is any update available in the admin panel and for that, the previous user app will not work, you can force the customer from here by providing the minimum version for force update. That means if a customer has an app below this version the customers must need to update the app first. If you don't need a force update just insert here zero (0) and ignore it.") }}"></i>
                                    </label>
                                    <input type="number" min="0" step=".1" id="android_min_version" name="android_min_version"
                                           value="{{$config['min_version']??''}}" class="form-control"
                                           placeholder="{{ translate('EX: 4.0') }}">
                                </div>

                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{translate('save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h2 class="text-center">{{translate('IOS')}}</h2>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('app_store_config'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.system-setup.app_setting',['platform' => 'ios']):'javascript:'}}"
                            method="post">
                            @csrf
                            <div class="form-group mt-4">
                                <div class="my-2">
                                    <label
                                        class="text-dark font-weight-bold">{{ translate('Enable download link for web footer') }}</label>
                                    <label class="switch ml-3 ">
                                        <input type="checkbox" class="status" name="app_store_status"
                                               value="1" {{(isset($config) && $config['status']==1)?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <div class="my-2">
                                    <label class="text-dark"
                                           for="app_store_link">{{ translate('Download link') }}
                                    </label>
                                    <input type="text" id="app_store_link" name="app_store_link"
                                           value="{{$config['link']??''}}" class="form-control" placeholder="">
                                </div>

                                <div class="my-2">
                                    <label class="text-dark"
                                           for="ios_min_version">{{ translate('Minimum version for force update') }}
                                        <i class="tio-info text-danger" data-toggle="tooltip" data-placement="right"
                                           title="{{ \App\CentralLogics\translate("If there is any update available in the admin panel and for that, the previous user app will not work, you can force the customer from here by providing the minimum version for force update. That means if a customer has an app below this version the customers must need to update the app first. If you don't need a force update just insert here zero (0) and ignore it.") }}"></i>
                                    </label>
                                    <input type="number" min="0" step=".1" id="ios_min_version" name="ios_min_version"
                                           value="{{$config['min_version']??''}}" class="form-control"
                                           placeholder="{{ translate('EX: 4.0') }}">
                                </div>

                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{translate('save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('script_2')

@endpush
