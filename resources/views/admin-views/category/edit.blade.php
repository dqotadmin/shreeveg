@extends('layouts.admin.app')

@section('title', translate('Update category'))

@push('css_or_js')
<style>
.bold-text {
    font-weight: bold;
}
</style>
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
                    @if($category->parent_id == 0)
                        {{ translate('Category Update') }}
                    @else
                    {{ translate('Sub Category Update') }}
                    @endif
                </span>
            </h1>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body">

                <form action="{{route('admin.category.update',[$category['id']])}}" method="post" enctype="multipart/form-data">

                    <div class="row align-items-end g-4" style="margin-top: 20px;">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Select Category Type') }}</label>

                                <div class="d-flex flex-wrap align-items-center form-control border">
                                    <label class="form-check form--check mr-2 mr-md-4 mb-0">
                                        <input type="radio" class="form-check-input" name="category_type" id="category_type1" value="parent" {{(isset($category['parent_id']) && $category['parent_id']==0)?'checked':''}} > 
                                        <span class="form-check-label"> {{ translate('Parent Category') }}</span>
                                    </label>
                                    <label class="form-check form--check mb-0">
                                        <input type="radio" class="form-check-input" name="category_type" id="category_type2" value="child" {{(isset($category['parent_id']) && $category['parent_id']!=0)?'checked':''}}>
                                        <span class="form-check-label"> {{ translate('Child Category') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5 {{(isset($category['parent_id']) && $category['parent_id']!=0)?'':'d-none'}}" id="category_box">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Select Parent Category')}}</label>
                                <select name="parent_id" id="parent_id" class="form-control" >
                                    <option value="">---{{translate('Select Parent Category')}}---</option>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    @csrf
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                    @if($data && array_key_exists('code', $data[0]))
                        <ul class="nav nav-tabs d-inline-flex mb--n-30">
                            @foreach($data as $lang)
                            <li class="nav-item">
                                <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#"
                                id="{{$lang['code']}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                            </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="row align-items-end g-4" style="margin-top: 40px;">

                        @if($data && array_key_exists('code', $data[0]))

                            @foreach($data as $lang)

                                <?php
                                    if (count($category['translations'])) {
                                        $translate = [];
                                        foreach ($category['translations'] as $t) {
                                            if ($t->locale == $lang['code'] && $t->key == "name") {
                                                $translate[$lang['code']]['name'] = $t->value;
                                            }

                                            if ($t->locale == $lang['code'] && $t->key == "title_silver") {
                                                $translate[$lang['code']]['title_silver'] = $t->value;
                                            }

                                            if ($t->locale == $lang['code'] && $t->key == "title_gold") {
                                                $translate[$lang['code']]['title_gold'] = $t->value;
                                            }

                                            if ($t->locale == $lang['code'] && $t->key == "title_platinum") {
                                                $translate[$lang['code']]['title_platinum'] = $t->value;
                                            }
                                        }
                                    }
                                ?>
                                
                                <div class="row {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}
                                                ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="name[]" maxlength="255" value="{{$lang['code'] == 'en' ? $category['name'] : ($translate[$lang['code']]['name']??'')}}" class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif placeholder="{{ translate('New Category') }}" {{$lang['status'] == true ? '':''}}>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('title_silver')}}
                                                ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="title_silver[]" maxlength="255" value="{{$lang['code'] == 'en' ? $category['title_silver'] : ($translate[$lang['code']]['title_silver']??'')}}" class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif placeholder="{{ translate('title_silver') }}" {{$lang['status'] == true ? '':''}}>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('title_gold')}}
                                                ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="title_gold[]" maxlength="255" value="{{$lang['code'] == 'en' ? $category['title_gold'] : ($translate[$lang['code']]['title_gold']??'')}}" class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif placeholder="{{ translate('title_gold') }}" {{$lang['status'] == true ? '':''}}>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('title_platinum')}}
                                                ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="title_platinum[]" maxlength="255" value="{{$lang['code'] == 'en' ? $category['title_platinum'] : ($translate[$lang['code']]['title_platinum']??'')}}" class="form-control" @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif placeholder="{{ translate('title_platinum') }}" {{$lang['status'] == true ? '':''}}>
                                        </div>
                                    </div>


                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                            @endforeach

                            
                        
                        @else
                            <div class="col-sm-6 lang_form" id="{{$default_lang}}-form">
                                <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('name')}}
                                    ({{strtoupper($default_lang)}})</label>
                                <input type="text" name="name[]" value="{{$lang['code'] == 'en' ? $category['name'] : ($translate[$lang['code']]['name']??'')}}"
                                        class="form-control" oninvalid="document.getElementById('en-link').click()"
                                        placeholder="{{ translate('New Category') }}" >
                            </div>
                            <input type="hidden" name="lang[]" value="{{$default_lang}}">
                        @endif
                        <input name="position" value="0" hidden>

                    </div>

                    <div class="row align-items-end g-4">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Category Code')}}</label>
                                <input type="text" name="category_code" value="{{$lang['code'] == 'en' ? $category['category_code'] : ($category['category_code']??'')}}"
                                        class="form-control input-text-uc" oninvalid="document.getElementById('en-link').click()"
                                        placeholder="{{ translate('Category Code') }}" >
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-end g-4">
                        
                        <div class="col-sm-3">
                            <center>
                                <img class="img--105" id="viewer" onerror="this.src='{{asset('public/category/img1.jpg')}}'"
                                src="{{asset('storage/app/public/category/')}}/{{$category['image']}}" alt="image"/>
                            </center>
                            <label>{{\App\CentralLogics\translate('image')}}</label><small style="color: red">* ( {{\App\CentralLogics\translate('ratio')}} 3:1 )</small>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileEg1">{{\App\CentralLogics\translate('choose')}} {{\App\CentralLogics\translate('file')}}</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                            <a href="{{route('admin.category.list')}}" type="reset" class="btn btn--reset">
                        {{translate('Back')}}</a>
                                <button type="submit" class="btn btn--primary">{{translate('Update')}}</button>
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
