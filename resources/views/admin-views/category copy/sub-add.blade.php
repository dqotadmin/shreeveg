@extends('layouts.admin.app')

@section('title', translate('Add new sub category'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    {{translate('sub_category_setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.category.store')}}" method="post">
                            @csrf
                            @php($data = Helpers::get_business_settings('language'))
                            @php($default_lang = Helpers::get_default_language())


                                <!-- <ul class="nav nav-tabs mb-4 d-inline-flex">
                                    @foreach($data as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#" id="{{$lang['code']}}-link">
                                                {{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                        </li>
                                    @endforeach
                                </ul> -->
                                <div class="row">
                                <input name="position" value="1" hidden>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="exampleFormControlSelect1">{{translate('main')}} {{translate('category')}}
                                            <span class="input-label-secondary">*</span></label>
                                        <select id="exampleFormControlSelect1" name="parent_id" class="form-control" required>
                                            @foreach(\App\Model\Category::orderBy('id', 'DESC')->where(['position'=>0])->get() as $category)
                                                <option value="{{$category['id']}}">{{$category['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @foreach($data as $lang)
                                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                        <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">
                                            {{translate('sub_category')}} {{translate('name')}}
                                             <!-- ({{strtoupper($lang['code'])}}) -->
                                        </label>
                                        <input type="text" name="name[]" class="form-control" maxlength="255" placeholder="{{ translate('New Sub Category') }}" {{$lang['status'] == true ? 'required':''}}
                                        @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach
                              
                                @foreach($data as $lang)
                                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">
                                            {{translate('Item')}} {{translate('Code')}}
                                             <!-- ({{strtoupper($lang['code'])}}) -->
                                        </label>
                                        <input type="text" name="item_code[]" class="form-control" maxlength="255" placeholder="{{ translate('abc000') }}" {{$lang['status'] == true ? 'required':''}}
                                        @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach

                               
                            
                                @foreach($data as $lang)
                                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">
                                            {{translate('Title silver')}} 
                                             <!-- ({{strtoupper($lang['code'])}}) -->
                                        </label>
                                        <input type="text" name="title_silver[]" class="form-control" maxlength="255" placeholder="{{ translate('abc000') }}" {{$lang['status'] == true ? 'required':''}}
                                        @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach

                                @foreach($data as $lang)
                                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">
                                            {{translate('Title Gold')}} 
                                             <!-- ({{strtoupper($lang['code'])}}) -->
                                        </label>
                                        <input type="text" name="title_gold[]" class="form-control" maxlength="255" placeholder="{{ translate('abc000') }}" {{$lang['status'] == true ? 'required':''}}
                                        @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach

                                @foreach($data as $lang)
                                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">
                                            {{translate('Title Platnum')}} 
                                             <!-- ({{strtoupper($lang['code'])}}) -->
                                        </label>
                                        <input type="text" name="title_platinum[]" class="form-control" maxlength="255" placeholder="{{ translate('abc000') }}" {{$lang['status'] == true ? 'required':''}}
                                        @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach

                                @foreach($data as $lang)
                                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">
                                            {{translate('Unit')}} {{translate('Title')}}
                                             <!-- ({{strtoupper($lang['code'])}}) -->
                                        </label>
                                        <input type="text" name="unit_title[]" class="form-control" maxlength="255" placeholder="{{ translate('abc000') }}" {{$lang['status'] == true ? 'required':''}}
                                        @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach

                                @foreach($data as $lang)
                                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">
                                            {{translate('Sub Unit select')}} 
                                             <!-- ({{strtoupper($lang['code'])}}) -->
                                        </label>
                                        <select id="exampleFormControlSelect1" name="sub_unit_title[]" class="form-control" required>
                                            @foreach(\App\Model\Unit::orderBy('id', 'DESC')->where(['position'=>0])->get() as $unit)
                                                <option value="{{$unit['id']}}">{{$unit['title']}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach
                                @foreach($data as $lang)

                                <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                <div class="form-group">
                                        <label class="form-label" for="unitselect">{{translate('Multiple Unit')}} {{translate('Select')}} </label>
                                        <select id="unitselect" name="unit[]" class="form-control chosen-select" multiple required>
                                            @foreach(\App\Model\Unit::orderBy('id', 'DESC')->where(['position'=>0])->get() as $unit)
                                                <option value="{{$unit['id']}}"
                                                >{{$unit['title']}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                </div>
                                @endforeach
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <div>
                                        <div class="text-center mb-3">
                                            <img id="viewer" class="img--105" src="{{ asset('public/assets/admin/img/160x160/1.png') }}" alt="image" />
                                        </div>
                                    </div>
                                    <label class="form-label text-capitalize">{{ translate('category image') }}</label><small class="text-danger">* ( {{ translate('ratio') }} 3:1 )</small>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required oninvalid="document.getElementById('en-link').click()">
                                        <label class="custom-file-label" for="customFileEg1">{{ translate('choose') }}{{ translate('file') }}</label>
                                    </div>
                                    </div>
                                </div>
                                 <div class="col-12">
                                    <div class="btn--container justify-content-end">
                                        <a href="" class="btn btn--reset min-w-120px">{{translate('reset')}}</a>
                                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>

    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.category.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
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
