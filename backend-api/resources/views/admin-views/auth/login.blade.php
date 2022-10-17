<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{translate('Admin')}} | {{translate('Login')}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/toastr.css">

    <style>
        .input-icons i {
            position: absolute;
            cursor: pointer;
        }

        .input-icons {
            width: 100%;
            margin-bottom: 10px;
        }

        .icon {
            padding: 9% 0 0 0;
            min-width: 40px;
        }

        .input-field {
            width: 94%;
            padding: 10px 0 10px 10px;
            text-align: center;
            border-right-style: none;
        }
    </style>
</head>

<body>
<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main" class="main">
    <div class="position-fixed top-0 right-0 left-0 bg-img-hero"
         style="height: 100%; background-image: url({{asset('public/assets/admin')}}/svg/components/login-background.png);">
    </div>

    <!-- Content -->
    <div class="container py-5 py-sm-7">
        <label class="badge badge-soft-success float-right" style="z-index: 9;position: absolute;right: 0.5rem;top: 0.5rem;">{{translate('Software version')}} : {{ env('SOFTWARE_VERSION') }}</label>
        <a class="d-flex justify-content-center mb-5" href="javascript:">
            <img class="z-index-2"
                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                 src="{{asset('storage/app/public/restaurant')}}/{{\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value??''}}" alt="Image Description" style="height: 100px;">
        </a>

        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <!-- Card -->
                <div class="card card-lg mb-5">
                    <div class="card-body">
                        <!-- Form -->
                        <form class="" id="form-id" action="{{route('admin.auth.login')}}" method="post">
                            @csrf

                            <div class="text-center">
                                <div class="mb-5">
                                    <h1 class="display-4"> {{translate('sign_in')}}</h1>
                                    <p>{{translate('want to login your branches?')}}
                                        <a href="{{route('branch.auth.login')}}">
                                            {{translate('branch')}} {{translate('login')}}
                                        </a>
                                    </p>
                                </div>
                                {{--<a class="btn btn-lg btn-block btn-white mb-4" href="#">
                                    <span class="d-flex justify-content-center align-items-center">
                                      <img class="avatar avatar-xss mr-2"
                                           src="{{asset('public/assets/admin')}}/svg/brands/google.svg" alt="Image Description">
                                      Sign in with Google
                                    </span>
                                </a>
                                <span class="divider text-muted mb-4">OR</span>--}}
                            </div>

                            <!-- Form Group -->
                            <div class="js-form-message form-group">
                                <label class="input-label text-capitalize" for="signinSrEmail">{{translate('your')}} {{translate('email')}}</label>

                                <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail"
                                       tabindex="1" placeholder="{{translate('email@address.com')}}" aria-label="email@address.com"
                                       required data-msg="Please enter a valid email address.">
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="js-form-message form-group">
                                <label class="input-label" for="signupSrPassword" tabindex="0">
                                    <span class="d-flex justify-content-between align-items-center">
                                      {{translate('password')}}
                                    </span>
                                </label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control form-control-lg"
                                           name="password" id="signupSrPassword" placeholder="{{translate('8+ characters required')}}"
                                           aria-label="8+ characters required" required
                                           data-msg="Your password is invalid. Please try again."
                                           data-hs-toggle-password-options='{
                                                     "target": "#changePassTarget",
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": "#changePassIcon"
                                            }'>
                                    <div id="changePassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:">
                                            <i id="changePassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Checkbox -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                           name="remember">
                                    <label class="custom-control-label text-muted" for="termsCheckbox">
                                        {{translate('remember')}} {{translate('me')}}
                                    </label>
                                </div>
                            </div>
                            <!-- End Checkbox -->

                            {{-- recaptcha --}}
                            @php($recaptcha = \App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                            @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                <div id="recaptcha_element" style="width: 100%;" data-type="image"></div>
                                <br/>
                            @else
                                <div class="row p-2">
                                    <div class="col-6 pr-0">
                                        <input type="text" class="form-control form-control-lg" name="default_captcha_value" value=""
                                               placeholder="{{translate('Enter captcha value')}}" style="border: none" autocomplete="off">
                                    </div>
                                    <div class="col-6 input-icons" style="background-color: #FFFFFF; border-radius: 5px;">
                                        <a onclick="javascript:re_captcha();">
                                            <img src="{{ URL('/admin/auth/code/captcha/1') }}" class="input-field" id="default_recaptcha_id" style="display: inline;width: 90%; height: 75%">
                                            <i class="tio-refresh icon"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif


                            <button type="submit" class="btn btn-lg btn-block btn-primary">{{translate('sign_in')}}</button>
                        </form>
                        <!-- End Form -->
                    </div>
                    @if(env('APP_MODE')=='demo')
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-10">
                                    <span>{{translate('Email : admin@admin.com')}}</span><br>
                                    <span>{{translate('Password : 12345678')}}</span>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-primary" onclick="copy_cred()"><i class="tio-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
    <!-- End Content -->
</main>
<!-- ========== END MAIN CONTENT ========== -->


<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<!-- JS Plugins Init. -->
<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });
    });
</script>

{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ \App\CentralLogics\Helpers::get_business_settings('recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script>
        $("#form-id").on('submit',function(e) {
            var response = grecaptcha.getResponse();

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{translate('Please check the recaptcha')}}");
            }
        });
    </script>
@else
    <script type="text/javascript">
        function re_captcha() {
            $url = "{{ URL('/admin/auth/code/captcha') }}";
            $url = $url + "/" + Math.random();
            document.getElementById('default_recaptcha_id').src = $url;
            console.log('url: '+ $url);
        }
    </script>
@endif
{{-- recaptcha scripts end --}}

@if(env('APP_MODE')=='demo')
    <script>
        function copy_cred() {
            $('#signinSrEmail').val('admin@admin.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('{{\App\CentralLogics\translate("Copied successfully!")}}', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endif

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
</body>
</html>
