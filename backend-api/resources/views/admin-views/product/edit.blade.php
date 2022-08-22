@extends('layouts.admin.app')

@section('title', translate('Update product'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i
                            class="tio-edit"></i> {{translate('product update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="javascript:" method="post" id="product_form" enctype="multipart/form-data">
                    @csrf
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                    @if($data && array_key_exists('code', $data[0]))
                        <ul class="nav nav-tabs mb-4">

                            @foreach($data as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang['code'] == 'en'? 'active':''}}" href="#" id="{{$lang['code']}}-link">{{Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                </li>
                            @endforeach

                        </ul>
                        @foreach($data as $lang)
                            <?php
                            if(count($product['translations'])){
                                $translate = [];
                                foreach($product['translations'] as $t)
                                {
                                    if($t->locale == $lang['code'] && $t->key=="name"){
                                        $translate[$lang['code']]['name'] = $t->value;
                                    }
                                    if($t->locale == $lang['code'] && $t->key=="description"){
                                        $translate[$lang['code']]['description'] = $t->value;
                                    }

                                }
                            }
                            ?>
                            <div class="card p-4 {{$lang['code'] != 'en'? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                <div class="form-group">
                                    <label class="input-label" for="{{$lang['code']}}_name">{{translate('name')}} ({{strtoupper($lang['code'])}})</label>
                                    <input type="text" {{$lang['status'] == true ? 'required':''}} name="name[]" id="{{$lang['code']}}_name" value="{{$translate[$lang['code']]['name']??$product['name']}}" class="form-control" placeholder="{{translate('New Product')}}" >
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                <div class="form-group pt-4">
                                    <label class="input-label"
                                           for="{{$lang['code']}}_description">{{translate('short')}} {{translate('description')}}  ({{strtoupper($lang['code'])}})</label>
{{--                                    <div id="{{$lang}}_editor" style="min-height: 15rem;">{!! $translate[$lang]['description']??$product['description'] !!}</div>--}}
                                    <textarea name="description[]" style="min-height: 15rem;width: 100%" id="{{$lang['code']}}_hiddenArea">{{$translate[$lang['code']]['description']??$product['description']}}</textarea>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="card p-4" id="english-form">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} (EN)</label>
                                <input type="text" name="name[]" value="{{$product['name']}}" class="form-control" placeholder="{{translate('New Product')}}" required>
                            </div>
                            <input type="hidden" name="lang[]" value="en">
                            <div class="form-group pt-4">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('short')}} {{translate('description')}} (EN)</label>
{{--                                <div id="editor" style="min-height: 15rem;">{!! $product['description'] !!}</div>--}}
                                <textarea name="description[]" style="min-height: 15rem;width: 100%" id="hiddenArea">{{ $product['description'] }}</textarea>
                            </div>
                        </div>
                    @endif
                    <div id="from_part_2">
                        <div class="row">
                            <div class="col-md-4 col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('price')}}</label>
                                    <input type="number" value="{{$product['price']}}" min="0" name="price"
                                           class="form-control" step="0.01"
                                           placeholder="{{translate('Ex : 100')}}" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('tax')}}</label>
                                    <input type="number" value="{{$product['tax']}}" min="0" name="tax"
                                           class="form-control" step="0.01"
                                           placeholder="{{translate('Ex : 7')}}" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('tax')}} {{translate('type')}}</label>
                                    <select name="tax_type" class="form-control js-select2-custom">
                                        <option value="percent" {{$product['tax_type']=='percent'?'selected':''}}>{{translate('percent')}}
                                        </option>
                                        <option value="amount" {{$product['tax_type']=='amount'?'selected':''}}>{{translate('amount')}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('discount')}}</label>
                                    <input type="number" min="0" value="{{$product['discount']}}"
                                           name="discount" class="form-control" required
                                           placeholder="Ex : 100">
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('discount')}} {{translate('type')}}</label>
                                    <select name="discount_type" class="form-control js-select2-custom">
                                        <option value="percent" {{$product['discount_type']=='percent'?'selected':''}}>
                                            {{translate('percent')}}
                                        </option>
                                        <option value="amount" {{$product['discount_type']=='amount'?'selected':''}}>
                                            {{translate('amount')}}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('item')}} {{translate('type')}}</label>
                                    <select name="item_type" class="form-control js-select2-custom">
                                        <option value="0" {{$product['set_menu']==0?'selected':''}}>{{translate('product')}} {{translate('item')}}</option>
                                        <option value="1" {{$product['set_menu']==1?'selected':''}}>{{translate('set_menu')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('category')}}<span
                                            class="input-label-secondary">*</span></label>
                                    <select name="category_id" id="category-id" class="form-control js-select2-custom"
                                            onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-categories')">
                                        @foreach($categories as $category)
                                            <option
                                                value="{{$category['id']}}" {{ $category->id==$product_category[0]->id ? 'selected' : ''}} >{{$category['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('sub_category')}}<span
                                            class="input-label-secondary"></span></label>
                                    <select name="sub_category_id" id="sub-categories"
                                            data-id="{{count($product_category)>=2?$product_category[1]->id:''}}"
                                            class="form-control js-select2-custom"
                                            onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-sub-categories')">

                                    </select>
                                </div>
                            </div>
                            {{--<div class="col-md-4 col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlSelect1">Sub Sub Category<span
                                            class="input-label-secondary"></span></label>
                                    <select name="sub_sub_category_id" id="sub-sub-categories"
                                            data-id="{{count($product_category)>=3?$product_category[2]->id:''}}"
                                            class="form-control js-select2-custom">

                                    </select>
                                </div>
                            </div>--}}
                        </div>

                        <div class="row" style="border: 1px solid #80808045; border-radius: 10px;padding-top: 10px;margin: 1px">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('attribute')}}<span
                                            class="input-label-secondary"></span></label>
                                    <select name="attribute_id[]" id="choice_attributes"
                                            class="form-control js-select2-custom"
                                            multiple="multiple">
                                        @foreach(\App\Model\Attribute::orderBy('name')->get() as $attribute)
                                            <option
                                                value="{{$attribute['id']}}" {{in_array($attribute->id,json_decode($product['attributes'],true))?'selected':''}}>{{$attribute['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2 mb-2">
                                <div class="customer_choice_options" id="customer_choice_options">
                                    @include('admin-views.product.partials._choices',['choice_no'=>json_decode($product['attributes']),'choice_options'=>json_decode($product['choice_options'],true)])
                                </div>
                            </div>
                            <div class="col-md-12 mt-2 mb-2">
                                <div class="variant_combination" id="variant_combination">
                                    @include('admin-views.product.partials._edit-combinations',['combinations'=>json_decode($product['variations'],true)])
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('addon')}}<span
                                            class="input-label-secondary"></span></label>
                                    <select name="addon_ids[]" class="form-control js-select2-custom" multiple="multiple">
                                        @foreach(\App\Model\AddOn::orderBy('name')->get() as $addon)
                                            <option
                                                value="{{$addon['id']}}" {{in_array($addon->id,json_decode($product['add_ons'],true))?'selected':''}}>{{$addon['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('available')}} {{translate('time')}} {{translate('starts')}}</label>
                                    <input type="time" value="{{$product['available_time_starts']}}"
                                           name="available_time_starts" class="form-control"
                                           placeholder="{{translate('Ex : 10:30 am')}}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('available')}} {{translate('time')}} {{translate('ends')}}</label>
                                    <input type="time" value="{{$product['available_time_ends']}}"
                                           name="available_time_ends" class="form-control" placeholder="{{translate('5:45 pm')}}"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{translate('product')}} {{translate('image')}}</label><small style="color: red">* ( {{translate('ratio')}} 1:1 )</small>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileEg1">{{translate('choose')}} {{translate('file')}}</label>
                            </div>

                            <center style="display: block" id="image-viewer-section" class="pt-2">
                                <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                     src="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                                     onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                     alt="product image"/>
                            </center>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">{{translate('update')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')

@endpush

@push('script_2')
    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>

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
            $('#image-viewer-section').show(1000)
        });
    </script>

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
            if(lang == 'en')
            {
                $("#from_part_2").removeClass('d-none');
            }
            else
            {
                $("#from_part_2").addClass('d-none');
            }


        })
    </script>
    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 4,
                rowHeight: '215px',
                groupClassName: 'col-3',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate("Please only input png or jpg type file")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate("File size too big")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>

    <script>
        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

        $(document).ready(function () {
            setTimeout(function () {
                let category = $("#category-id").val();
                let sub_category = '{{count($product_category)>=2?$product_category[1]->id:''}}';
                let sub_sub_category = '{{count($product_category)>=3?$product_category[2]->id:''}}';
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + category + '&&sub_category=' + sub_category, 'sub-categories');
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + sub_category + '&&sub_category=' + sub_sub_category, 'sub-sub-categories');
            }, 1000)
        });
    </script>

    <script>
        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script src="{{asset('public/assets/admin')}}/js/tags-input.min.js"></script>

    <script>
        $('#choice_attributes').on('change', function () {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function () {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="Choice Title" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>');
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        function combination_update() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{route('admin.product.variant-combination')}}',
                data: $('#product_form').serialize(),
                success: function (data) {
                    $('#variant_combination').html(data.view);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }
    </script>

{{--    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>--}}

    <script>
{{--        @if($language)--}}
{{--        @foreach(json_decode($language) as $lang)--}}
{{--        var {{$lang}}_quill = new Quill('#{{$lang}}_editor', {--}}
{{--            theme: 'snow'--}}
{{--        });--}}
{{--        @endforeach--}}
{{--        @else--}}
{{--        var en_quill = new Quill('#editor', {--}}
{{--            theme: 'snow'--}}
{{--        });--}}
{{--        @endif--}}

        $('#product_form').on('submit', function () {

{{--            @if($language)--}}
{{--            @foreach(json_decode($language) as $lang)--}}
{{--            var {{$lang}}_myEditor = document.querySelector('#{{$lang}}_editor');--}}
{{--            $("#{{$lang}}_hiddenArea").val({{$lang}}_myEditor.children[0].innerHTML);--}}
{{--            @endforeach--}}
{{--            @else--}}
{{--            var myEditor = document.querySelector('#editor');--}}
{{--            $("#hiddenArea").val(myEditor.children[0].innerHTML);--}}
{{--            @endif--}}

            var formData = new FormData(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.product.update',[$product['id']])}}',
                // data: $('#product_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{translate("product updated successfully!")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.product.list')}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush
