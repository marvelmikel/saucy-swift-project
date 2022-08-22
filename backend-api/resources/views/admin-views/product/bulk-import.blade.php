@extends('layouts.admin.app')

@section('title', translate('Product Bulk Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{route('admin.product.list')}}">{{translate('product')}}</a>
                </li>
                <li class="breadcrumb-item">{{translate('bulk_import')}} </li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-12">
                <div class="jumbotron" style="background: white">
                    <h1 class="display-4">{{translate('Instructions :')}} </h1>
                    <p>{{translate('1. Download the format file and fill it with proper data.')}}</p>

                    <p>{{translate('2. You can download the example file to understand how the data must be filled.')}}</p>

                    <p>{{translate('3. Once you have downloaded and filled the format file, upload it in the form below and submit.')}}</p>

                    <p>{{\App\CentralLogics\translate("4. After uploading products you need to edit them and set product's images and choices.")}}</p>

                    <p>{{translate('5. You can get category and sub-category id from their list, please input the right ids.')}}</p>

                </div>
            </div>

            <div class="col-md-12">
                <form class="product-form" action="{{route('admin.product.bulk-import')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card mt-2 rest-part">
                        <div class="card-header">
                            <h4>{{translate('Import Products File')}}</h4>
                            <a href="{{asset('public/assets/product_bulk_format.xlsx')}}" download=""
                               class="btn btn-secondary">{{translate('Download Format')}}</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="file" name="products_file">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-footer">
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 20px">
                                <button type="submit" class="btn btn-primary">{{translate('Submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
