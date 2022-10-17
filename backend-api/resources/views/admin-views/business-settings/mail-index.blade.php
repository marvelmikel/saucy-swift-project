@extends('layouts.admin.app')

@section('title', translate('Settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('SMTP Mail Setup')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3 card card-body">
            <div class="col-lg-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">

                        <div class="row mb-4">
                            <div class="col-10">
                                <button class="btn btn-secondary" type="button" data-toggle="collapse"
                                        data-target="#collapseExample" aria-expanded="false"
                                        aria-controls="collapseExample">
                                    <i class="tio-email-outlined"></i>
                                    {{translate('test_your_email_integration')}}
                                </button>
                            </div>
                            <div class="col-2 float-right">
                                <i class="tio-telegram float-right"></i>
                            </div>
                        </div>

                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form class="" action="javascript:">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group mb-2">
                                                <label for="inputPassword2"
                                                       class="sr-only">{{translate('mail')}}</label>
                                                <input type="email" id="test-email" class="form-control"
                                                       placeholder="{{translate('Ex : jhon@email.com')}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <button type="button" onclick="send_mail()" class="btn btn-primary mb-2 btn-block">
                                                <i class="tio-telegram"></i>
                                                {{translate('send_mail')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6"></div>

            @php($data= \App\CentralLogics\Helpers::get_business_settings('mail_config'))
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.mail-config'):'javascript:'}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($data))
                        <div class="form-group mb-2 mt-2">
                            <input type="radio" name="status"
                                   value="1" {{isset($data['status']) && $data['status']==1?'checked':''}}>
                            <label style="padding-left: 10px">{{translate('Active')}}</label>
                            <br>
                        </div>
                        <div class="form-group mb-2">
                            <input type="radio" name="status"
                                   value="0" {{isset($data['status']) && $data['status']==0?'checked':''}}>
                            <label style="padding-left: 10px">{{translate('Inactive')}}</label>
                            <br>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 2px">{{translate('mailer')}} {{translate('name')}}</label><br>
                            <input type="text" placeholder="{{translate('ex : Alex')}}" class="form-control" name="name"
                                   value="{{env('APP_MODE')!='demo'?$data['name']:''}}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label style="padding-left: 2px">{{translate('host')}}</label><br>
                            <input type="text" class="form-control" name="host" value="{{env('APP_MODE')!='demo'?$data['host']:''}}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label style="padding-left: 2px">{{translate('driver')}}</label><br>
                            <input type="text" class="form-control" name="driver" value="{{env('APP_MODE')!='demo'?$data['driver']:''}}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label style="padding-left: 2px">{{translate('port')}}</label><br>
                            <input type="text" class="form-control" name="port" value="{{env('APP_MODE')!='demo'?$data['port']:''}}" required>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 2px">{{translate('username')}}</label><br>
                            <input type="text" placeholder="{{translate('ex : ex@yahoo.com')}}" class="form-control" name="username"
                                   value="{{env('APP_MODE')!='demo'?$data['username']:''}}" required>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 2px">{{translate('email')}} {{translate('id')}}</label><br>
                            <input type="text" placeholder="{{translate('ex : ex@yahoo.com')}}" class="form-control" name="email"
                                   value="{{env('APP_MODE')!='demo'?$data['email_id']:''}}" required>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 2px">{{translate('encryption')}}</label><br>
                            <input type="text" placeholder="{{translate('ex : tls')}}" class="form-control" name="encryption"
                                   value="{{env('APP_MODE')!='demo'?$data['encryption']:''}}" required>
                        </div>

                        <div class="form-group mb-2">
                            <label style="padding-left: 2px">{{translate('password')}}</label><br>
                            <input type="text" class="form-control" name="password" value="{{env('APP_MODE')!='demo'?$data['password']:''}}" required>
                        </div>

                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary mb-2 mt-2">{{translate('save')}}</button>
                    @else
                        <button type="submit" class="btn btn-primary mb-2">{{translate('configure')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

    <script>
        function ValidateEmail(inputText) {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (inputText.match(mailformat)) {
                return true;
            } else {
                return false;
            }
        }

        function send_mail() {
            if (ValidateEmail($('#test-email').val())) {
                Swal.fire({
                    title: '{{translate('Are you sure?')}}?',
                    text: "{{translate('a_test_mail_will_be_sent_to_your_email')}}!",
                    showCancelButton: true,
                    confirmButtonColor: '#F56A57',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{translate('Yes')}}!',
                    cancelButtonText: '{{translate('Cancel')}}!',
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{route('admin.business-settings.web-app.mail-send')}}",
                            method: 'POST',
                            data: {
                                "email": $('#test-email').val()
                            },
                            beforeSend: function () {
                                $('#loading').show();
                            },
                            success: function (data) {
                                if (data.success === 2) {
                                    toastr.error('{{translate('email_configuration_error')}} !!');
                                } else if (data.success === 1) {
                                    toastr.success('{{translate('email_configured_perfectly!')}}!');
                                } else {
                                    toastr.info('{{translate('email_status_is_not_active')}}!');
                                }
                            },
                            complete: function () {
                                $('#loading').hide();

                            }
                        });
                    }
                })
            } else {
                toastr.error('{{translate('invalid_email_address')}} !!');
            }
        }
    </script>
@endpush
