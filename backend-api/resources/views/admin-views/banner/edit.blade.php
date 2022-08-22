@extends('layouts.admin.app')

@section('title', translate('Update banner'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{translate('Update Banner')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.banner.update',[$banner['id']])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf @method('put')

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                                <input type="text" name="title" value="{{$banner['title']}}" class="form-control"
                                       placeholder="{{translate('New banner')}}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('item type')}}<span
                                        class="input-label-secondary">*</span></label>
                                <select name="item_type" class="form-control" onchange="show_item(this.value)">
                                    <option value="product" {{$banner['product_id']==null?'':'selected'}}>{{translate('product')}}</option>
                                    <option value="category" {{$banner['category_id']==null?'':'selected'}}>{{translate('category')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{translate('banner image')}}</label><small style="color: red">* ( {{translate('ratio 3:1')}} )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1">{{translate('choose file')}}</label>
                                </div>
                                <div class="text-center mt-3">
                                    <img style="width: 100%;height:auto;border: 1px solid; border-radius: 10px;" id="viewer"
                                         src="{{asset('storage/app/public/banner')}}/{{$banner['image']}}"
                                         onerror="this.src='{{asset('public/assets/admin/img/1920x400/img2.jpg')}}'"
                                         alt="banner image"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group" id="type-product"
                                 style="display: {{$banner['product_id']==null?'none':'block'}}">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('product')}} <span
                                        class="input-label-secondary">*</span></label>
                                <select name="product_id" class="form-control js-select2-custom">
                                    @foreach($products as $product)
                                        <option
                                            value="{{$product['id']}}" {{$banner['product_id']==$product['id']?'selected':''}}>
                                            {{$product['name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="type-category"
                                 style="display: {{$banner['category_id']==null?'none':'block'}}">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('category')}} <span
                                        class="input-label-secondary">*</span></label>
                                <select name="category_id" class="form-control js-select2-custom">
                                    @foreach($categories as $category)
                                        <option value="{{$category['id']}}" {{$banner['category_id']==$category['id']?'selected':''}}>{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary">{{translate('update')}}</button>
                </form>
            </div>
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

        function show_item(type) {
            if (type === 'product') {
                $("#type-product").show();
                $("#type-category").hide();
            } else {
                $("#type-product").hide();
                $("#type-category").show();
            }
        }
    </script>
@endpush
