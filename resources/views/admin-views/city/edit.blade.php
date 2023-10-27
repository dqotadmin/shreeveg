@extends('layouts.admin.app')

@section('title', translate('Update unit'))

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
                        {{ translate('city Update') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.city.update',[$cities['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <!-- @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language()) -->

                  

                    @if($data && array_key_exists('code', $data[0]))
                    <div class="row align-items-end g-4">
                        @foreach($data as $lang)
                            <?php
                                if (count($cities['translations'])) {
                                    $translate = [];
                                    foreach ($cities['translations'] as $t) {
                                        if ($t->locale == $lang['code'] && $t->key == "title") {
                                            $translate[$lang['code']]['title'] = $t->value;
                                        }
                                    }
                                }
                            ?>
                            <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('city')}}
                                    <!-- ({{strtoupper($lang['code'])}}) -->
                                </label>
                                <input type="text" name="city[]" maxlength="255"
                                 value="{{$lang['code'] == 'en' ? $cities['city'] : ($translate[$lang['code']]['city']??'')}}" 
                                 class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif 
                                 placeholder="{{ translate('New city') }}" {{$lang['status'] == true ? 'required':''}}>
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                        @endforeach
                        @foreach ($data as $lang)
                                        <div class="col-sm-6 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                                id="{{ $lang['code'] }}-form">
                                            <label class="form-label"
                                                    for="exampleFormControlInput1">{{ translate('State') }}
                                                <!-- ({{ strtoupper($lang['code']) }}) -->
                                            </label>
                                            <select id="exampleFormControlSelect1" name="state_id[]" class="form-control "  required>
                                             @foreach(\App\Model\State::orderBy('id', 'DESC')->where(['position'=>0])->get() as $state)
                                                <option value="{{$state['id']}}"  {{$cities['state_id']==$state['id']?'selected':''}}>{{$state['name']}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                        

                        @else
                            <div class="col-sm-6 lang_form" id="{{$default_lang}}-form">
                                <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('name')}}
                                    ({{strtoupper($default_lang)}})</label>
                                <input type="text" name="name[]" value="{{$cities['name']}}"
                                        class="form-control" oninvalid="document.getElementById('en-link').click()"
                                        placeholder="{{ translate('New Category') }}" required>
                            </div>
                            <input type="hidden" name="lang[]" value="{{$default_lang}}">
                        @endif
                        <input name="position" value="0" hidden>
                        
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <a href="{{route('admin.city.add')}}"  type="reset" class="btn btn--reset">
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
