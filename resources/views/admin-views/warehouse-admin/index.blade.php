@extends('layouts.admin.app')

@section('title', translate('list'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--24" alt="">
            </span>
            <span>
            {{@$role->name}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
       @if( in_array(auth('admin')->user()->admin_role_id, [1,6]) && $role->id != auth('admin')->user()->admin_role_id)
       

            <div class="btn--container justify-content-end m-2">
                <a type="button" href="{{route('admin.user-management-create',['role_id'=>request('role_id')])}}"
                    class="btn btn--primary">{{translate('Add')}} {{$role->name}}</a>
            </div>
            @elseif(in_array(auth('admin')->user()->admin_role_id, [3]) && $role->id != auth('admin')->user()->admin_role_id && $role->id != 8)
            <div class="btn--container justify-content-end m-2">
                <a type="button" href="{{route('admin.user-management-create',['role_id'=>request('role_id')])}}"
                    class="btn btn--primary">{{translate('Add')}} {{$role->name}}</a>
            </div>   
             @endif
            @php($data = Helpers::get_business_settings('language'))
            @php($default_lang = Helpers::get_default_language())

        </div>
    </div>
</div>

<div class="col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="card--header">
            <h5 class="card-title">{{$role->name}}<span class="badge badge-soft-secondary">{{ $admins->total() }}</span> </h5>
                </h5>
                
                <form action="{{url()->current()}}" method="GET">
                    <div class="input-group">
                    <input type="hidden" name="role_id" value="{{ request('role_id') }}">
                        <input id="datatableSearch_" type="search" name="search" maxlength="255"
                            class="form-control pl-5" placeholder="{{translate('Search_by_Name')}}" aria-label="Search"
                            value="{{$search}}"  autocomplete="off" >
                        <i class="tio-search tio-input-search"></i>
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text">
                                {{translate('search')}}
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
                        <th class="text-center">{{translate('#')}}</th>
                        <th>{{translate('image')}}</th>
                        <th>{{translate('full_name')}}</th>
                        @if($role->id == '6' || $role->id == '7' )
                        <th>{{translate('assign warehouse by admin')}}</th>
                        @endif
                        @if($role->id == '3'   || $role->id == '5' || $role->id == '4')
                            <th>{{translate('warehouse')}}</th>
                        @elseif($role->id == '6' || $role->id == '7' )
                            <th>{{translate('store')}}</th>
                        @endif
                        <th>{{translate('Contact Info')}}</th>
                        <th>{{translate('status')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($admins as $key=>$admin)
                    @if(auth('admin')->user()->admin_role_id != '6')
                    <tr>
                        <td class="text-center">{{$admins->firstItem()+$key}}</td>
                        <td>
                            <img src="{{asset('storage/app/public/admin/warehouse')}}/{{$admin['image']}}"    onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'" class="img--50 ml-3" alt="">
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-50" style="text-transform: capitalize ;">
                                {{$admin['f_name'] }} {{ $admin['l_name']}}
                            </span>
                        </td>
                        @if($role->id == '6' || $role->id == '7' )

                        <td>
                            <span class="d-block font-size-sm text-body text-trim-50" style="text-transform: capitalize ;">
                            {{ @$admin->Store->warehouse->name}}

                            </span>
                        </td>
                        @endif
                        @if($role->id == '3'   || $role->id == '5' || $role->id == '4')
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-50" style="text-transform: capitalize ;">
                                @if($admin->warehouse_id > 0 && !empty($admin->warehouse_id))
                                    {{$admin->Warehouse->name }}
                                @endif 
                           
                            </span>
                        </td>
                        @elseif($role->id == '6' || $role->id == '7' )
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-50" style="text-transform: capitalize ;">
                            
                            @if($admin->store_id > 0 && $admin->store_id)
                            {{$admin->Store->name }} 
                            @endif
                            </span>
                        </td>
                            @endif
                        <td>
                            <h5 class="m-0">
                                <a href="mailto:{{$admin['email']}}">{{$admin['email']}}</a>
                            </h5>
                            <div>
                                <a href="Tel:{{$admin['phone']}}">{{$admin['phone']}}</a>
                            </div>
                        </td>
                       
                        <td>
                        <label class="toggle-switch">
                            <input type="checkbox"
                                onclick="status_change_alert('{{ route('admin.admin-status', [$admin->id, $admin->status ? 0 : 1]) }}', '{{ $admin->status? translate('you_want_to_disable_this_admin'): translate('you_want_to_active_this_admin') }}', event)"
                                class="toggle-switch-input" id="stocksCheckbox{{ $admin->id }}"
                                {{ $admin->status ? 'checked' : '' }}>
                            <span class="toggle-switch-label text">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                         

                        </td>
                        <td>
                            <!-- Dropdown -->
                            <div class="btn--container justify-content-center">
                                @if( auth('admin')->user()->admin_role_id == 1 && $admin['admin_role_id'] ==8)
                                <a class="action-btn" href="{{route('admin.broker-history',[$admin->id])}}">
                                    <i class="tio-invisible"></i>
                                </a>        
                                @endif
                                           
                                            <a class="action-btn"
                                                href="{{route('admin.admin-edit',[$admin['id'],'role_id'=>$role->id])}}">
                                            <i class="tio-edit"></i></a>
                                        @if( auth('admin')->user()->admin_role_id == 1)
                                            <a class="action-btn btn--danger btn-outline-danger" href="javascript:"
                                                onclick="form_alert('admin-{{$admin['id']}}','{{ translate("Want to delete this") }}')">
                                                <i class="tio-delete-outlined"></i>
                                            </a>
                                        </div>
                                        <form action="{{route('admin.admin-delete',[$admin['id']])}}"
                                                method="post" id="admin-{{$admin['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                        @endif
                            <!-- End Dropdown -->
                        </td>
                    </tr>
                    @elseif(auth('admin')->user()->admin_role_id == '6' && $admin->store_id == auth('admin')->user()->store_id)
                    <tr>
                        <td class="text-center">{{$admins->firstItem()+$key}}</td>
                        <td>
                            
                        <img src="{{asset('storage/app/public/admin/warehouse')}}/{{$admin['image']}}"
                                            onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'" class="img--50 ml-3" alt="">
                        </td>
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-50" style="text-transform: capitalize ;">
                                {{$admin['f_name'] }} {{ $admin['l_name']}}
                            </span>
                        </td>

                    
                        @if($role->id == '6' || $role->id == '7' )

                        <td>
                            <span class="d-block font-size-sm text-body text-trim-50" style="text-transform: capitalize ;">
                            {{ @$admin->Store->warehouse->name}}
                          

                            </span>
                        </td>
                        @endif
                        @if($role->id == '3'   || $role->id == '5' || $role->id == '4')
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-50" style="text-transform: capitalize ;">
                                @if($admin->warehouse_id > 0 && !empty($admin->warehouse_id))
                                    {{$admin->Warehouse->name }}
                                @endif 
                           
                            </span>
                        </td>
                        @elseif($role->id == '6' || $role->id == '7' )
                        <td>
                            <span class="d-block font-size-sm text-body text-trim-50" style="text-transform: capitalize ;">
                            
                            @if($admin->store_id > 0 && $admin->store_id)
                            {{$admin->Store->name }} 
                            @endif
                            </span>
                        </td>
                            @endif
                        <td>
                            <h5 class="m-0">
                                <a href="mailto:{{$admin['email']}}">{{$admin['email']}}</a>
                            </h5>
                            <div>
                                <a href="Tel:{{$admin['phone']}}">{{$admin['phone']}}</a>
                            </div>
                        </td>
                       
                        <td>
                        <label class="toggle-switch">
                            <input type="checkbox"
                                onclick="status_change_alert('{{ route('admin.admin-status', [$admin->id, $admin->status ? 0 : 1]) }}', '{{ $admin->status? translate('you_want_to_disable_this_admin'): translate('you_want_to_active_this_admin') }}', event)"
                                class="toggle-switch-input" id="stocksCheckbox{{ $admin->id }}"
                                {{ $admin->status ? 'checked' : '' }}>
                            <span class="toggle-switch-label text">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                         

                        </td>
                        <td>
                            <!-- Dropdown -->
                            <div class="btn--container justify-content-center">
                                            <a class="action-btn"
                                                href="{{route('admin.admin-edit',[$admin['id'],'role_id'=>$role->id])}}">
                                            <i class="tio-edit"></i></a>
                                        @if( auth('admin')->user()->admin_role_id == 1)
                                            <a class="action-btn btn--danger btn-outline-danger" href="javascript:"
                                                onclick="form_alert('admin-{{$admin['id']}}','{{ translate("Want to delete this") }}')">
                                                <i class="tio-delete-outlined"></i>
                                            </a>
                                        </div>
                                        <form action="{{route('admin.admin-delete',[$admin['id']])}}"
                                                method="post" id="admin-{{$admin['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                        @endif
                            <!-- End Dropdown -->
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>


            @if(count($admins) == 0)
            <div class="text-center p-4">
                <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
                <p class="mb-0">{{translate('No_data_to_show')}}</p>
            </div>
            @endif
            <table>
                <tfoot>
                {!! $admins->links() !!}
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
function status_change_alert(url, message, e) {
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: message,
        type: 'warning',
        showCancelButton: true,
        cancelButtonColor: 'default',
        confirmButtonColor: '#107980',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            location.href = url;
        }
    })
}
</script>

<script>
$(".lang_link").click(function(e) {
    e.preventDefault();
    $(".lang_link").removeClass('active');
    $(".lang_form").addClass('d-none');
    $(this).addClass('active');

    let form_id = this.id;
    let lang = form_id.split("-")[0];
    console.log(lang);
    $("#" + lang + "-form").removeClass('d-none');
    if (lang == '{{$default_lang}}') {
        $(".from_part_2").removeClass('d-none');
    } else {
        $(".from_part_2").addClass('d-none');
    }
});
</script>

<script>
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#viewer').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#customFileEg1").change(function() {
    readURL(this);
});
</script>
@endpush