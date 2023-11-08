@extends('layouts.admin.app')

@section('title', translate('Update area'))

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
                {{ translate('area') }}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->


    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.area.update',[$cityareas['id']])}}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row align-items-end g-4">

                    <div class="col-sm-6">
                        <label class="form-label" for="exampleFormControlInput1">{{ translate('City') }}

                        </label>
                        <select id="exampleFormControlSelect1" name="city_id" class="form-control " required>
                            @foreach(\App\Model\City::orderBy('id', 'DESC')->get() as $city)
                            <option value="{{$city['id']}}" {{$city['id']==$cityareas['city_id']?'selected':''}}>
                                {{$city['city']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label" for="exampleFormControlInput1">{{ translate('Area') }}
                            {{ translate('Name') }}
                        </label>
                        <input type="text" name="area" class="form-control"
                            placeholder="{{ translate('Ex:jhotwara') }}" maxlength="255"
                            value="{{$cityareas['area']}}">
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label" for="exampleFormControlInput1">{{ translate('latitude') }}
                            {{ translate('code') }}
                        </label>
                        <input type="text" name="latitude_code" class="form-control"
                            placeholder="{{ translate('Ex: 24.958302') }}" maxlength="255"
                            value="{{$cityareas['latitude_code']}}">
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label" for="exampleFormControlInput1">{{ translate('longitude') }}
                            {{ translate('code') }}
                        </label>
                        <input type="text" name="longitude_code" class="form-control"
                            placeholder="{{ translate('Ex: 75.743347') }}" maxlength="255"
                            value="{{$cityareas['longitude_code']}}">
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label" for="exampleFormControlInput1">{{ translate('radius') }}
                        </label>
                        <input type="text" name="radius" class="form-control" placeholder="{{ translate('') }}"
                            maxlength="255" value="{{$cityareas['radius']}}">
                    </div>

                    <div class="col-12">
                        <div class="btn--container justify-content-end">
                            <a href="{{route('admin.area.add')}}" type="reset" class="btn btn--reset">
                                {{translate('Back')}}</a>
                            <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                        </div>
                    </div>
                </div>
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