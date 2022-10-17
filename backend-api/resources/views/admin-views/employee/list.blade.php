@extends('layouts.admin.app')

@section('title', translate('Employee List'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{translate('Employee')}}</li>
            <li class="breadcrumb-item" aria-current="page">{{translate('List')}}</li>
        </ol>
    </nav>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex-between">
                    <div class="">
                        <h5>{{translate('employee_table')}}
                            <span style="color: red; padding: 0 .4375rem;">
                            ({{$em->total()}})</span>
                        </h5>
                    </div>
                    <div class="flex-end">
                        <div class="mx-2">
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
                        <div>
                            <a href="{{route('admin.employee.add-new')}}" class="btn btn-primary  float-right">
                                <i class="tio-add-circle"></i>
                                <span class="text">{{translate('Add')}} {{translate('New')}}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding: 0">
                    <div class="table-responsive">
                        <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               style="width: 100%">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('SL')}}#</th>
                                <th>{{translate('Name')}}</th>
                                <th>{{translate('Email')}}</th>
                                <th>{{translate('Phone')}}</th>
                                <th>{{translate('Role')}}</th>
                                <th>{{translate('Status')}}</th>
                                <th style="width: 50px">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($em as $k=>$e)
                            @if($e->role)
                                <tr>
                                    <th scope="row">{{$k+1}}</th>
                                    <td class="text-capitalize">{{$e['f_name'] . ' ' . $e['l_name']}}</td>
                                    <td >
                                      {{$e['email']}}
                                    </td>
                                    <td>{{$e['phone']}}</td>
                                    <td>{{$e->role['name']}}</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                            <input type="checkbox" class="toggle-switch-input"
                                                   onclick="location.href='{{route('admin.employee.status',[$e['id'],$e->status?0:1])}}'"
                                                   class="toggle-switch-input" {{$e->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                        </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="{{route('admin.employee.update',[$e['id']])}}"
                                           class="btn btn-primary btn-sm"
                                           title="{{translate('Edit')}}">
                                           <i class="tio-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{$em->links()}}
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
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
