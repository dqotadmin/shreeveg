@extends('layouts.admin.app')

@section('title', translate('Add New Unit'))

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
                    {{translate('Unit')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row g-2">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body pt-sm-0 pb-sm-4">
                        <form action="{{route('admin.unit.store')}}"  method="post" id="timeForm" enctype="multipart/form-data" class="needs-validation form_customer" novalidate>
                            @csrf
                            @php($data = Helpers::get_business_settings('language'))
                            @php($default_lang = Helpers::get_default_language())
                            {{-- @php($default_lang = 'en') --}}
                           
                                </ul>
                                <div class="row align-items-end g-4" style="margin-top: 40px;">
                                        <div class="col-sm-6 ">
                                            <label class="form-label" for="exampleFormControlInput1">{{ translate('Unit') }} {{ translate('Title') }}
                                            </label>
                                            <input type="text" name="title" class="form-control" value="{{old('title')}}" placeholder="{{ translate('Ex: gm') }}" maxlength="255" required>
                                            <div class="invalid-feedback">
                                                Please enter unit title.
                                            </div>
                                        </div>
                                  
                                        <div class="col-sm-6" >
                                            <label class="form-label"  for="exampleFormControlInput1">{{ translate('Unit') }} {{ translate('Description') }}
                                            </label>
                                            <input type="text" name="description" class="form-control" value="{{old('description')}}" placeholder="{{ translate('Ex: gram') }}" maxlength="255" required>
                                            <div class="invalid-feedback">
                                                Please enter unit description.
                                            </div>
                                        </div>
                                    
                                    <input name="position" value="0" hidden>
                              
                                    <div class="col-12">
                                        <div class="btn--container justify-content-end">
                                        <a type="button" href="{{route('admin.unit.list')}}" class="btn btn--reset">{{translate('Back')}}</a>
                                            <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
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

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
