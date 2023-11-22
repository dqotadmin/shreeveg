@extends('layouts.admin.app')

@section('title', translate('Update city'))

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
                {{ translate('Update city') }}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->


    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.city.update',[$cities['id']])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row align-items-end g-4" style="padding-top: 50px;">


                    <div class="col-sm-6 ">{{translate('city')}}
                        </label>
                        <input type="text" name="city" maxlength="255" value="{{$cities['city']}}" class="form-control"
                            placeholder="{{ translate('New city') }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="">{{ translate('City') }} {{ translate('Code') }}
                        </label>
                        <input type="text" name="city_code" class="form-control"  value="{{$cities['city_code']}}"
                            placeholder="{{ translate('Ex: 0141') }}" maxlength="255">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{ translate('State') }}
                        </label>
                        <select id="exampleFormControlSelect1" name="state_id" class="form-control " required>
                            @foreach(\App\Model\State::orderBy('id', 'DESC')->get() as $state)
                            <option value="{{$state['id']}}" {{$cities['state_id']==$state['id']?'selected':''}}>
                                {{$state['name']}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-12">
                        <div class="btn--container justify-content-end">
                            <a href="{{route('admin.city.add')}}" type="reset" class="btn btn--reset">
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
$(document).on('ready', function() {
    $('.js-select2-custom').each(function() {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});
</script>
@endpush