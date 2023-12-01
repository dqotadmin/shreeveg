@extends('layouts.admin.app')

@section('title', translate('Add new city'))

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
                {{translate('city')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->

    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-body pt-sm-0 pb-sm-4">
                    <form action="{{route('admin.city.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-end g-4" style="padding-top: 50px;">
                            <div class="col-sm-6">
                                <label class="form-label" for="">{{ translate('City') }}
                                </label>
                                <input type="text" name="city" class="form-control"
                                    placeholder="{{ translate('Ex: City') }}" maxlength="255">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="">{{ translate('City') }} {{ translate('Code') }}
                                </label>
                                <input type="text" name="city_code" class="form-control"
                                    placeholder="{{ translate('Ex: City Code') }}" maxlength="255">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="">{{ translate('State') }}
                                </label>
                                <select id=" " name="state_id" class="form-control js-select2-custom" required>
                                    <option value="" disabled selected>Select State </option>
                                    @foreach(\App\Model\State::where('status','1')->orderBy('id', 'DESC')->get() as $state)
                                    <option value="{{$state['id']}}">{{$state['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="btn--container justify-content-end">
                                    <a href="{{route('admin.city.list')}}" type="reset" class="btn btn--reset">
                                        {{translate('Back')}}</a>
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
$(document).on('ready', function() {
    $('.js-select2-custom').each(function() {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});
</script>
@endpush