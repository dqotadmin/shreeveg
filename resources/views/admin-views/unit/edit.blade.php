@extends('layouts.admin.app')

@section('title', translate('Update Unit'))

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
                {{ translate('Update Unit') }}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->


    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.unit.update',[$units['id']])}}" method="post" enctype="multipart/form-data">
            @csrf
                   
                    <div class="row align-items-end g-4" style="margin-top: 40px;">
                      <div class="col-sm-6 ">
                            <label class="form-label" for=" ">{{ translate('Unit') }} {{ translate('Title') }}
                            </label>
                            <input type="text" name="title" class="form-control"  value="{{$units['title']}}"
                                placeholder="{{ translate('Ex: gm') }}" maxlength="255" >
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label" for=" ">{{ translate('Unit') }}
                                {{ translate('Description') }}
                            </label>
                            <input type="text" name="description" class="form-control" value="{{$units->description}}"
                                placeholder="{{ translate('Ex: gram') }}" maxlength="255">
                        </div>
                      
                        <input name="position" value="0" hidden>

                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
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