@extends('layouts.admin.app')

@section('title', translate('Add new category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{translate('add New Category')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.category.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                    @if ($data && array_key_exists('code', $data[0]))
                        <ul class="nav nav-tabs mb-4">
                            @foreach ($data as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{ $lang['default'] == true ? 'active' : '' }}" href="#"
                                       id="{{ $lang['code'] }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="row">
                            <div class="col-12">
                                @foreach ($data as $lang)
                                    <div class="form-group {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                         id="{{ $lang['code'] }}-form">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('name') }}
                                            ({{ strtoupper($lang['code']) }})</label>
                                        <input type="text" name="name[]" class="form-control" placeholder="{{ translate('New Category') }}" maxlength="255"
                                               {{$lang['status'] == true ? 'required':''}}
                                               @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                @endforeach
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group lang_form" id="{{ $default_lang }}-form">
                                                <label class="input-label"
                                                       for="exampleFormControlInput1">{{ translate('name') }}
                                                    ({{ strtoupper($default_lang) }})</label>
                                                <input type="text" name="name[]" class="form-control" maxlength="255"
                                                       placeholder="{{ translate('New Category') }}" required>
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{ $default_lang }}">
                                            @endif
                                            <input name="position" value="0" style="display: none">
                                        </div>
                                        <div class="row col-md-6 col-12">
                                            <div class="col-12 from_part_2">
                                                <label>{{ translate('image') }}</label><small style="color: red">* ( {{ translate('ratio') }} 3:1 )</small>
                                                <div class="custom-file">
                                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required
                                                           oninvalid="document.getElementById('en-link').click()">
                                                    <label class="custom-file-label" for="customFileEg1">{{ translate('choose file') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-12 from_part_2 mt-2">
                                                <div class="form-group">
                                                    <div class="text-center">
                                                        <img style="height:170px;border: 1px solid; border-radius: 10px;" id="viewer"
                                                             src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}" alt="image" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-md-6 col-12">
                                            <div class="col-12 from_part_2">
                                                <label>{{ translate('banner image') }}</label><small style="color: red">* ( {{ translate('ratio') }} 8:1 )</small>
                                                <div class="custom-file">
                                                    <input type="file" name="banner_image" id="customFileEg2" class="custom-file-input"
                                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required
                                                           oninvalid="document.getElementById('en-link').click()">
                                                    <label class="custom-file-label" for="customFileEg2">{{ translate('choose file') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-12 from_part_2 mt-2 px-4">
                                                <div class="form-group">
                                                    <div class="text-center">
                                                        <img style="width: 100%;height:170px;border: 1px solid; border-radius: 10px;" id="viewer2"
                                                             src="{{ asset('public/assets/admin/img/1920x400/img2.jpg') }}" alt="image" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header flex-between">
                        <div class="flex-start">
                            <h5 class="card-header-title">{{translate('Category Table')}}</h5>
                            <h5 class="card-header-title text-primary mx-1">({{ $categories->total() }})</h5>
                        </div>
                        <div>
                            <form action="{{url()->current()}}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                           class="form-control"
                                           placeholder="{{translate('Search')}}" aria-label="Search"
                                           value="{{$search}}" required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text"><i class="tio-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th style="width: 50%">{{translate('name')}}</th>
                                <th style="width: 20%">{{translate('status')}}</th>
                                <th style="width: 10%">{{translate('action')}}</th>
                            </tr>
                            <tr>
                                <th colspan="2"></th>
                                <th>
                                    <select id="column3_search" class="js-select2-custom"
                                            data-hs-select2-options='{
                                              "minimumResultsForSearch": "Infinity",
                                              "customClass": "custom-select custom-select-sm text-capitalize"
                                            }'>
                                        <option value="">{{translate('any')}}</option>
                                        <option value="Active">{{translate('active')}}</option>
                                        <option value="Disabled">{{translate('disabled')}}</option>
                                    </select>
                                </th>
                                <th>
                                    {{--<input type="text" id="column4_search" class="form-control form-control-sm"
                                           placeholder="Search countries">--}}
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($categories as $key=>$category)
                                <tr>
                                    <td>{{$categories->firstitem()+$key}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{$category['name']}}
                                    </span>
                                    </td>
                                    <td>
                                        @if($category['status']==1)
                                            <div style="padding: 10px;border: 1px solid;cursor: pointer"
                                                 onclick="location.href='{{route('admin.category.status',[$category['id'],0])}}'">
                                                <span class="legend-indicator bg-success"></span>active
                                            </div>
                                        @else
                                            <div style="padding: 10px;border: 1px solid;cursor: pointer"
                                                 onclick="location.href='{{route('admin.category.status',[$category['id'],1])}}'">
                                                <span class="legend-indicator bg-danger"></span>disabled
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                   href="{{route('admin.category.edit',[$category['id']])}}">{{translate('edit')}}</a>
                                                <a class="dropdown-item" href="javascript:"
                                                   onclick="form_alert('category-{{$category['id']}}','{{translate("Want to delete this")}}')">{{translate('delete')}}</a>
                                                <form action="{{route('admin.category.delete',[$category['id']])}}"
                                                      method="post" id="category-{{$category['id']}}">
                                                    @csrf @method('delete')
                                                </form>
                                            </div>
                                        </div>
                                        <!-- End Dropdown -->
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                            {!! $categories->links() !!}
                            </tfoot>
                        </table>

                    </div>
                </div>
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
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            // var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            var datatable = $('.table').DataTable({
                "paging": false
            });

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
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
