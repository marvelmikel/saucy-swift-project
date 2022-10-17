@extends('layouts.admin.app')

@section('title', translate('Update category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i>
                        @if($category->parent_id == 0)
                            {{translate('category Update')}}</h1>
                        @else
                            {{translate('Sub Category Update')}}</h1>
                        @endif
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.category.update',[$category['id']])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                    @if($data && array_key_exists('code', $data[0]))
                        <ul class="nav nav-tabs mb-4">
                            @foreach($data as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#"
                                       id="{{$lang['code']}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="row">
                            <div class="col-12">
                                @foreach($data as $lang)
                                    <?php
                                    if (count($category['translations'])) {
                                        $translate = [];
                                        foreach ($category['translations'] as $t) {
                                            if ($t->locale == $lang['code'] && $t->key == "name") {
                                                $translate[$lang['code']]['name'] = $t->value;
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="form-group {{$lang['default'] == false ? 'd-none':''}} lang_form"
                                         id="{{$lang['code']}}-form">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{translate('name')}}
                                            ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="name[]" maxlength="255"
                                               value="{{$lang['code'] == 'en' ? $category['name'] : ($translate[$lang['code']]['name']??'')}}"
                                               class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif
                                               placeholder="{{ translate('New Category') }}" {{$lang['status'] == true ? 'required':''}}>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group lang_form" id="{{$default_lang}}-form">
                                                <label class="input-label"
                                                       for="exampleFormControlInput1">{{translate('name')}}
                                                    ({{strtoupper($default_lang)}})</label>
                                                <input type="text" name="name[]" value="{{$category['name']}}"
                                                       class="form-control" oninvalid="document.getElementById('en-link').click()"
                                                       placeholder="{{ translate('New Category') }}" required>
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{$default_lang}}">
                                            @endif
                                            <input name="position" value="0" style="display: none">
                                        </div>
                                        @if($category->parent_id == 0)
                                            <div class="row col-md-6 col-12">
                                                <div class="col-12 from_part_2">
                                                    <label>{{ translate('image') }}</label><small style="color: red">* ( {{ translate('ratio') }} 3:1 )</small>
                                                    <div class="custom-file">
                                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                               oninvalid="document.getElementById('en-link').click()">
                                                        <label class="custom-file-label" for="customFileEg1">{{ translate('choose file') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 from_part_2 mt-2">
                                                    <div class="form-group">
                                                        <div class="text-center">
                                                            <img style="height:170px;border: 1px solid; border-radius: 10px;" id="viewer"
                                                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                                 src="{{asset('storage/app/public/category')}}/{{$category['image']}}" alt="image" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row col-md-6 col-12">
                                                <div class="col-12 from_part_2">
                                                    <label>{{ translate('banner image') }}</label><small style="color: red">* ( {{ translate('ratio') }} 8:1 )</small>
                                                    <div class="custom-file">
                                                        <input type="file" name="banner_image" id="customFileEg2" class="custom-file-input"
                                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                               oninvalid="document.getElementById('en-link').click()">
                                                        <label class="custom-file-label" for="customFileEg2">{{ translate('choose file') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 from_part_2 mt-2 px-4">
                                                    <div class="form-group">
                                                        <div class="text-center">
                                                            <img style="height:170px;border: 1px solid; border-radius: 10px;" id="viewer2"
                                                                 onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                                                 src="{{asset('storage/app/public/category/banner')}}/{{$category['banner_image']}}" alt="image" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="submit"
                                            class="btn btn-primary mt-2">{{translate('update')}}</button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>
    <script>
        function readURL(input, viewer_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer_id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });
        $("#customFileEg2").change(function () {
            readURL(this, 'viewer2');
        });
    </script>
@endpush
