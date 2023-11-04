@extends('layouts.admin.app')

@section('title', translate('Assign categories'))

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
                {{ translate('warehouse Update setup ') }}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->


    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="row g-2">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="tio-user"></i>
                                {{translate('Warehouse information')}}
                            </h5>
                        </div>

                        <div class="card-body pt-sm-0 pb-sm-4">
                            @php($data = Helpers::get_business_settings('language'))
                            @php($default_lang = Helpers::get_default_language())
                            {{-- @php($default_lang = 'en') --}}
                            {{-- @php($default_lang = json_decode($language)[0]) --}}
                            <ul class="nav nav-tabs d-inline-flex mb--n-30">
                                <!-- @foreach ($data as $lang)
                                                <li class="nav-item">
                                                    <a class="nav-link lang_link {{ $lang['default'] == true ? 'active' : '' }}" href="#"
                                                    id="{{ $lang['code'] }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                                </li>
                                                @endforeach -->
                            </ul>
                            <input type="hidden" id="prev_id" value=" ">
                            <div class="row align-items-end g-4">
                                @foreach ($data as $lang)
                                <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                    id="{{ $lang['code'] }}-form">
                                    <label class="form-label"
                                        for="exampleFormControlInput1">{{ translate('Select Warehouse Admin') }}
                                        <!-- ({{ strtoupper($lang['code']) }}) -->
                                    </label>
                                    @foreach(\App\Model\Admin::orderBy('id',
                                    'DESC')->where('admin_role_id',3)->get() as $admin)
                                    @if($warehouses->owner_name ==$admin->id)
                                    <input class="form-control" value="{{$admin['f_name']}} {{$admin['l_name']}}"
                                        readonly>
                                    @endif
                                    @endforeach
                                </div>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                @endforeach
                                @foreach ($data as $lang)
                                <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                    id="{{ $lang['code'] }}-form">
                                    <label class="form-label"
                                        for="exampleFormControlInput1">{{ translate('Warehouse Name') }}
                                        <!-- ({{ strtoupper($lang['code']) }}) -->
                                    </label>
                                    <input type="text" name="name[]" class="form-control" maxlength="255"
                                        {{$lang['status'] == true ? '':''}} @if($lang['status']==true)
                                        oninvalid="document.getElementById('{{$lang['code']}}-link').click()"
                                        value="{{$warehouses->name}}" readonly @endif>
                                </div>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                @endforeach
                                <!-- <p>Data Attribute Value: <span id="dataAttributeValue"></span></p> -->
                                @foreach ($data as $lang)
                                <div class="col-sm-4 {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                    id="{{ $lang['code'] }}-form">
                                    <label class="form-label"
                                        for="exampleFormControlInput1">{{ translate('Warehouse Code') }}
                                    </label>
                                    <!-- <a href="javascript:void(0)" class="float-right c1 fz-12"
                                            onclick="generateCode()">{{translate('generate_code')}}</a> -->
                                    <input type="text" name="code[]" class="form-control" readonly
                                        value="{{$warehouses->code}}" id="dataAttributeValue"
                                        placeholder="{{\Illuminate\Support\Str::random(8)}}">
                                </div>
                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="tio-category"></i>
                                {{translate('Category Assign')}}
                            </h5>
                        </div>
                     
                        <div class="card-body">
                            <form id="submit-create-role" method="post" action="{{route('admin.warehouse.wh-assign-category')}}"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                @csrf
                                <input type="hidden" name="warehouse_id" value="{{$warehouses->id}}">
                                <div class="d-flex">
                                    <h5 class="input-label m-0 text-capitalize">{{translate('module_permission')}} :
                                    </h5>
                                    <div class="check-item pb-0 w-auto">
                                        <input type="checkbox" id="select_all">
                                        <label class="title-color mb-0 pl-2"
                                            for="select_all">{{ translate('select_all')}}</label>
                                    </div>
                                </div>

                                <div class="check--item-wrapper" style="display: inherit;">
                                    <div class="row">
                                        <?php $i=1; ?>
                                            <!-- @foreach($wh_assign_categories as $wh_assign_category)
                                           
                                            <div class="col-md-6">
                                                    <div class="check_category_main">
                                                        <div class="check-item pb-0">
                                                            <div class="form-group form-check form--check">
                                                                <input type="checkbox" name="category_id[]"
                                                                    value="{{$wh_assign_category->id}}"
                                                                    class="form-check-input module-permission"
                                                                    id="{{$wh_assign_category->category_id}}" checked>
                                                                <label class="form-check-label" 
                                                                    style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                                 
                                                                    for="{{$wh_assign_category->category_id}}">{{translate($wh_assign_category->category_id)}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="m_inputs">
                                                            <input type="number" name="category_order[]"  value="{{$wh_assign_category->category_order}}"
                                                      
                                                                placeholder="Ex. Order <?php  ?>"  class="form-control " id="">

                                                            <input type="text" name="margin[]" value="{{$wh_assign_category->margin}}" placeholder="Ex. Margin 5%"
                                                                class="form-control" id="">

                                                            <label class="toggle-switch my-0">
                                                                <input type="checkbox"
                                                                    onclick="status_change_alert('{{ route('admin.warehouse.wh-assign-category-status', [$warehouses->id, $warehouses->status ? 0 : 1]) }}', '{{ $warehouses->status? translate('you_want_to_disable_this_role'): translate('you_want_to_active_this_role') }}', event)"
                                                                    class="toggle-switch-input" id="stocksCheckbox{{ $warehouses->id }}"
                                                                    {{ $warehouses->status ? 'checked' : '' }}><span class="toggle-switch-label mx-auto text"><span class="toggle-switch-indicator"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                             
                                            @endforeach -->
                                        @foreach($categories as $category)
                                       

                                       
                                        <?php
                                        $editRow = Helpers::getWhCategoriesData($category->id,$warehouses->id);
                                        $checked = $margin = $catOrder=''; $status ='1'; 
                                            if(!empty($editRow) && $editRow->category_id == $category->id){
                                                $checked ='checked';
                                                $margin = $editRow->margin;
                                                $catOrder = $editRow->category_order;
                                                $status = true;
                                            }
                                        
                                        ?>
    
                                       
                                        <div class="col-md-6">
                                            <div class="check_category_main">
                                                <div class="check-item pb-0">
                                                    <div class="form-group form-check form--check">
                                                        <input type="checkbox" {{$checked}} name="category_id[]"
                                                            value="{{$category->id}}"
                                                            class="form-check-input module-permission"
                                                            id="{{$category->name}}">
                                                        <label class="form-check-label"
                                                            style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                            for="{{$category->name}}">{{translate($category->name)}}</label>
                                                    </div>
                                                </div>

                                                <div class="m_inputs">
                                                    <input type="number" name="category_order[]" value="{{$catOrder}}"
                                                        placeholder="Ex. Order <?php  ?>" class="form-control " id="">

                                                    <input type="text" name="margin[]" value="{{$margin}}" placeholder="Ex. Margin 5%"
                                                        class="form-control" id="">
                                                    
                                                    <label class="toggle-switch my-0">
                                                        <input type="checkbox"
                                                        {{ $checked }}
                                                            class="toggle-switch-input" name="status[]" id="stocksCheckbox{{ $warehouses->id }}"
                                                           ><span class="toggle-switch-label mx-auto text"><span class="toggle-switch-indicator"></span>
                                                        </span>
                                                    </label>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                       
                                        @endforeach
                                    </div>
                                </div>
                                <div class="btn--container justify-content-end mt-4">
                                    <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @push('script_2')


    <script>
    $(document).ready(function() {
        // Check or uncheck "Select All" based on other checkboxes
        $(".module-permission").on('change', function() {
            if ($(".module-permission:checked").length == $(".module-permission").length) {
                $("#select_all").prop("checked", true);
            } else {
                $("#select_all").prop("checked", false);
            }
        });

        // Check or uncheck all checkboxes based on "Select All" checkbox
        $("#select_all").on('change', function() {
            if ($("#select_all").is(":checked")) {
                $(".module-permission").prop("checked", true);
            } else {
                $(".module-permission").prop("checked", false);
            }
        });

        // Check "Select All" checkbox on page load if all checkboxes are checked
        if ($(".module-permission:checked").length == $(".module-permission").length) {
            $("#select_all").prop("checked", true);
        }
    });
    </script>
    @endpush