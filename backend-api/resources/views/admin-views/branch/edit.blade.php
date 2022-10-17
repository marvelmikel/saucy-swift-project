@extends('layouts.admin.app')

@section('title', translate('Update Branch'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize"><i
                            class="tio-edit"></i> {{translate('Branch Update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        @php($branch_count=\App\Model\Branch::count())
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.branch.update',[$branch['id']])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('name')}}</label>
                                <input type="text" name="name" value="{{$branch['name']}}" class="form-control"
                                       placeholder="{{translate('New branch')}}"
                                       maxlength="255" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('email')}}</label>
                                <input type="email" name="email" value="{{$branch['email']}}" class="form-control"
                                       placeholder="{{translate('EX : example@example.com')}}"
                                       maxlength="255" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                <label class="input-label" for="">{{translate('latitude')}}</label>
                                <input type="number" name="latitude" value="{{$branch['latitude']}}" class="form-control"
                                       placeholder="{{translate('Ex : -132.44442')}}" maxlength="255"
                                       step="any" {{$branch_count>1?'required':''}}>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label class="input-label" for="">{{translate('longitude')}}</label>
                                <input type="number" name="longitude" value="{{$branch['longitude']}}"
                                       class="form-control" maxlength="255" step="any"
                                       placeholder="{{translate('Ex : 94.233')}}"
                                    {{$branch_count>1?'required':''}}>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label class="input-label" for="">
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{translate('This value is the radius from your restaurant location, and customer can order food inside  the circle calculated by this radius.')}}"></i>
                                    {{translate('coverage (km)')}}
                                </label>
                                <input type="number" name="coverage" min="1" value="{{$branch['coverage']}}" max="1000"
                                       class="form-control" placeholder="{{translate('Ex : 3')}}"
                                    {{$branch_count>1?'required':''}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label" for="">{{translate('address')}}</label>
                                <input type="text" name="address" value="{{$branch['address']}}" class="form-control"
                                       placeholder="" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('password')}} <span
                                        class="" style="color: red;font-size: small">* ( {{translate('input if you want to reset.')}} )</span></label>
                                <input type="text" name="password" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{translate('Branch Image')}}</label><small style="color: red">* ( {{translate('ratio')}} 1:1 )</small>
                        <div class="custom-file">
                            <input type="file" name="image" id="customFileEg1" class="custom-file-input" value="{{$branch['image']}}"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileEg1">{{translate('Choose File')}}</label>
                        </div>
                        <div class="text-center mt-2">
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                 src="{{asset('storage/app/public/branch')}}/{{$branch['image']}}"
                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                 alt="branch image"/>
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>

@endpush
