@extends('layouts.admin.app')

@section('title', translate('Individual Group List'))

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
                {{translate('Individual Group List')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">

            @php($data = Helpers::get_business_settings('language'))
            @php($default_lang = Helpers::get_default_language())
            {{-- @php($default_lang = 'en') --}}
        </div>
    </div>

    <div class="col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="card--header">
                    <h5 class="card-title">{{translate('Individual Group List')}} <span
                            class="badge badge-soft-secondary">{{ $warehouses->total() }}</span>
                    </h5>
                </div>
            </div>
            <div class="card-header border-0">
                <div class="card--header">
                    <h5 class="card-title">{{translate('Group Name')}} <span class="badge badge-soft-info">
                            @foreach($warehouses as $key =>$warehouse)
                            <th> @if($key==0) {{$warehouse->group_name}} @endif</th>
                            @endforeach
                        </span>
                    </h5>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <form action="{{route('admin.warehouse-category.store-group-margin-individual')}}" method="post"
                    enctype="multipart/form-data">
                    @csrf <table
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th>{{translate('Category Name')}}</th>
                                <th>{{translate('Store Margin')}}</th>
                                <th>{{translate('Customer Margin')}}</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach($warehouses as $key=>$warehouse)

                            <tr>
                                <td class="text-center">{{$warehouses->firstItem()+$key}}</td>

                                <td>
                                    <span class="d-block font-size-sm text-body text-trim-50">
                                        {{$warehouse->getCategory->name}}
                                    </span>
                                    <input type="hidden" class="form-control" name="category_id[]"
                                        value="{{$warehouse->category_id}}" readonly>
                                        <input type="hidden" class="form-control" name="group_name[]"
                                        value="{{$warehouse->group_name}}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="store_margin[]"
                                        value="{{$warehouse->store_margin}}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="customer_margin[]"
                                        value="{{$warehouse->customer_margin}}" readonly>
                                </td>


                            </tr>


                            @endforeach

                        </tbody>

                    </table>
<!-- 
                    <div class="  justify-content-end mt-4">
                        <a type="button" href=" " class="btn btn--reset">{{translate('back')}}</a>
                        <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
                    </div> -->
                </form>

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