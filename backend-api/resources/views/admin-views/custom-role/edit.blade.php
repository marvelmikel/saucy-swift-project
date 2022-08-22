@extends('layouts.admin.app')

@section('title', translate('Edit Role'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('Dashboard')}}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{translate('Role Update')}}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="submit-create-role" action="{{route('admin.custom-role.update',[$role['id']])}}" method="post"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{translate('role_name')}}</label>
                                <input type="text" name="name" value="{{$role['name']}}" class="form-control" id="name"
                                       aria-describedby="emailHelp"
                                       placeholder="{{translate('Ex')}} : {{translate('Store')}}">
                            </div>

                            <label for="module">{{translate('module_permission')}} : </label>
                            <hr>
                            <div class="row">
                                @foreach(MANAGEMENT_SECTION as $section)
                                    <div class="col-md-3">
                                        <div class="form-group form-check">
                                            <input type="checkbox" name="modules[]" value="{{$section}}" class="form-check-input"
                                                   {{in_array($section,(array)json_decode($role['module_access']))?'checked':''}}
                                                   id="{{$section}}">
                                            <label class="form-check-label" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};" for="{{$section}}">{{translate($section)}}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary float-right">{{translate('update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
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
