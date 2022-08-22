@extends('layouts.admin.app')

@section('title', translate('Create Role'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{translate('custom_role')}}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{translate('role_form')}}
                    </div>
                    <div class="card-body">
                        <form id="submit-create-role" method="post" action="{{route('admin.custom-role.store')}}"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{translate('role_name')}}</label>
                                <input type="text" name="name" class="form-control" id="name"
                                       aria-describedby="emailHelp"
                                       placeholder="{{translate('Ex')}} : {{translate('Store')}}" required>
                            </div>

                            <label for="name">{{translate('module_permission')}} : </label>
                            <hr>
                            <div class="row">
                                @foreach(MANAGEMENT_SECTION as $section)
                                    <div class="col-md-3">
                                        <div class="form-group form-check">
                                            <input type="checkbox" name="modules[]" value="{{$section}}" class="form-check-input"
                                                   id="{{$section}}">
                                            <label class="form-check-label" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};" for="{{$section}}">{{translate($section)}}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary float-right">{{translate('Submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{translate('roles_table')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"
                                   style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <thead>
                                <tr>
                                    <th scope="col">{{translate('SL')}}#</th>
                                    <th scope="col">{{translate('role_name')}}</th>
                                    <th scope="col">{{translate('modules')}}</th>
                                    <th scope="col">{{translate('created_at')}}</th>
                                    <th scope="col">{{translate('status')}}</th>
                                    <th scope="col" style="width: 50px">{{translate('action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rl as $k=>$r)
                                    <tr>
                                        <th scope="row">{{$k+1}}</th>
                                        <td>{{$r['name']}}</td>
                                        <td class="text-capitalize">
                                            @if($r['module_access']!=null)
                                                @foreach((array)json_decode($r['module_access']) as $m)
                                                    {{str_replace('_',' ',$m)}} <br>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{{date('d-M-Y',strtotime($r['created_at']))}}</td>
                                        <td><span>{{$r['status'] == true ? translate(ACTIVE) : translate(INACTIVE)}}</span></td>
                                        <td>
                                            <a href="{{route('admin.custom-role.update',[$r['id']])}}"
                                               class="btn btn-primary btn-sm"
                                               title="{{translate('Edit') }}">
                                               <i class="tio-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
    <script>

        $('#submit-create-role').on('submit',function(e){

            var fields = $("input[name='modules[]']").serializeArray();
            if (fields.length === 0)
            {
                toastr.warning('{{ translate('select_minimum_one_selection_box') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                return false;
            }else{
                $('#submit-create-role').submit();
            }
        });
    </script>
@endpush
