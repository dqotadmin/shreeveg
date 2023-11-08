@extends('layouts.admin.app')

@section('title', translate('Add New Warehouse'))

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
                {{translate('Warehouse')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="btn--container justify-content-end m-2">
                <a type="button" href="{{route('admin.warehouse.create')}}"
                    class="btn btn--primary">{{translate('Create Warehouse')}}</a>
            </div>
            @php($data = Helpers::get_business_settings('language'))
            @php($default_lang = Helpers::get_default_language())
            {{-- @php($default_lang = 'en') --}}
        </div>
    </div>

    <div class="col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="card--header">
                    <h5 class="card-title">{{translate('Warehouses')}} <span
                            class="badge badge-soft-secondary">{{ $warehouses->total() }}</span>
                    </h5>
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input id="datatableSearch_" type="search" name="search" maxlength="255"
                                class="form-control pl-5" placeholder="{{translate('Search_by_Name')}}"
                                aria-label="Search"  value="{{$search}}" autocomplete="on">
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
                            <th>{{translate('Warehouse Name')}}</th>
                            <th>{{translate('Warehouse Code')}}</th>
                            <th>{{translate('Address')}}</th>
                            <!-- <th>{{translate('Pincode')}}</th> -->
                            <!-- <th>{{translate('Name')}}</th>
                            <th>{{translate('Number')}}</th>
                            <th>{{translate('Title')}}</th> -->
                            <th>{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($warehouses as $key=>$warehouse)
                        <tr>
                            <td class="text-center">{{$warehouses->firstItem()+$key}}</td>

                            <td>
                                <span class="d-block font-size-sm text-body text-trim-50">
                                    {{$warehouse['name']}}
                                </span>
                            </td>
                            <td>
                                <span class="d-block font-size-sm text-body text-trim-50">
                                    {{$warehouse['code']}}
                                </span>
                            </td>
                            <td>
                                <span class="d-block font-size-sm text-body text-trim-50">
                                    {{$warehouse['address']}}
                                </span>
                            </td>

                            <td>

                                <label class="toggle-switch">
                                    <input type="checkbox"
                                        onclick="status_change_alert('{{ route('admin.warehouse.status', [$warehouse->id, $warehouse->status ? 0 : 1]) }}', '{{ $warehouse->status? translate('you_want_to_disable_this_warehouse'): translate('you_want_to_active_this_warehouse') }}', event)"
                                        class="toggle-switch-input" id="stocksCheckbox{{ $warehouse->id }}"
                                        {{ $warehouse->status ? 'checked' : '' }}>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>

                            </td>
                            <td>
                                <!-- Dropdown -->

                                <div class="btn--container justify-content-center">
                                    <a class="action-btn btn--warning btn-outline-warning"
                                        href="{{route('admin.warehouse.wh-assign-category-page',[$warehouse['id']])}}">
                                        <i class="tio-category"></i></a>
                                    <a class="action-btn" href="{{route('admin.warehouse.edit',[$warehouse['id']])}}">
                                        <i class="tio-edit"></i></a>
                                    <a class="action-btn btn--danger btn-outline-danger" href="javascript:"
                                        onclick="form_alert('warehouse-{{$warehouse['id']}}','{{ translate("Want to delete this") }}')">
                                        <i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.warehouse.delete',[$warehouse['id']])}}" method="post"
                                    id="warehouse-{{$warehouse['id']}}">
                                    @csrf @method('delete')
                                </form>
                                <!-- End Dropdown -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                @if(count($warehouses) == 0)
                <div class="text-center p-4">
                    <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}"
                        alt="Image Description">
                    <p class="mb-0">{{translate('No_data_to_show')}}</p>
                </div>
                @endif

                <table>
                    <tfoot>
                        {!! $warehouses->links() !!}
                    </tfoot>
                </table>

                <table>
                    <tfoot>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
    <!-- End Table -->
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