@extends('layouts.admin.app')

@section('title', translate('Cancellation policy'))

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
            background-color: #673AB7;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #673AB7;
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
                <div class="col-sm mb-sm-0">
                    <h1 class="page-header-title">{{\App\CentralLogics\translate('Cancellation policy')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row" style="padding-bottom: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="padding: 20px">

                        <div class="mt-4">

                            <form
                                action="{{route('admin.business-settings.page-setup.cancellation_page_update')}}" id="tnc-form" method="post">
                                @csrf

                                <div class="my-2">
                                    <label class="text-dark font-weight-bold">{{ translate('Check Status') }}</label>
                                    <label class="switch ml-3 ">
                                        <input type="checkbox" class="status" name="status"
                                            value="1" {{ json_decode($data['value'],true)['status']==1?'checked':''}}
                                            >
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <div class="row gx-2 gx-lg-3">
                                    <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">

                                            <div class="form-group">
                                                <textarea class="ckeditor form-control" name="content">
                                                   {{ json_decode($data['value'],true)['content']}}
                                                </textarea>
                                            </div>
                                    </div>
                                </div>

                            </div>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary mb-2">{{\App\CentralLogics\translate('save')}}</button>

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('script_2')
@endpush


@push('script_2')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush
