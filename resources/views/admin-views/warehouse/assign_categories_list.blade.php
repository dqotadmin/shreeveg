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
                {{ translate('Assign categories') }}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="row g-2">
        <div class="col-sm-12 col-lg-12">
            <div class="btn--container justify-content-end m-2">
                <a type="button" href="{{route('admin.warehouse.wh-assign-category-page-create',[$warehouses['id']])}}"
                    class="btn btn--primary">{{translate('Category Assign')}}</a>
            </div>
            @php($data = Helpers::get_business_settings('language'))
            @php($default_lang = Helpers::get_default_language())
            {{-- @php($default_lang = 'en') --}}
        </div>
    </div>


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

                            <input type="hidden" id="prev_id" value=" ">
                            <div class="row align-items-end g-4" style="padding-top: 50px;">
                                <div class="col-sm-6">
                                    <label class="form-label"
                                        for="exampleFormControlInput1">{{ translate('Warehouse Name') }}
                                    </label>
                                    <input type="text" name="name[]" class="form-control" maxlength="255"
                                        value="{{$warehouses->name}}" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label"
                                        for="exampleFormControlInput1">{{ translate('Warehouse Code') }} </label>
                                    <input type="text" name="code[]" class="form-control" readonly
                                        value="{{$warehouses->code}}" id="dataAttributeValue">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="tio-category"></i>
                                {{translate('Category Assign List')}}
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive datatable-custom">
                                <table
                                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">{{translate('#')}}</th>
                                            <th>{{translate('Category Name')}}</th>
                                            <th>{{translate('Order')}}</th>
                                            <th>{{translate('Margin')}}</th>

                                            <th>{{translate('status')}}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($wh_assign_categories as $key => $wh_assign_category)
                                            @if($wh_assign_category->getCategory)
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>

                                            <td>
                                                <span class="d-block font-size-sm text-body text-trim-50">
                                                    {{$wh_assign_category->getCategory?$wh_assign_category->getCategory->name:''}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="d-block font-size-sm text-body text-trim-50">
                                                    {{$wh_assign_category['category_order']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="d-block font-size-sm text-body text-trim-50">
                                                    {{$wh_assign_category['margin']}}
                                                </span>
                                            </td>

                                            <td>

                                                <label class="toggle-switch">
                                                    <input type="checkbox" name="status[]"
                                                        onclick="status_change_alert('{{ route('admin.warehouse.wh-assign-category-status', [$wh_assign_category->id, $wh_assign_category->status ? 0 : 1]) }}', '{{ $wh_assign_category->status? translate('you_want_to_disable_this_category'): translate('you_want_to_active_this_category') }}', event)"
                                                        class="toggle-switch-input"
                                                        id="stocksCheckbox{{ $wh_assign_category->id }}"
                                                        {{ $wh_assign_category->status ? 'checked' : '' }}>


                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>

                                            </td>

                                       </tr>
                                        @endif
                                       @endforeach

                                    </tbody>
                                </table>


                                @if(count($wh_assign_categories) == 0)
                                <div class="text-center p-4">
                                    <img class="w-120px mb-3"
                                        src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}"
                                        alt="Image Description">
                                    <p class="mb-0">{{translate('No_data_to_show')}}</p>
                                </div>
                                @endif


                                <table>
                                    <tfoot>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- <form id="submit-create-role" method="post">
                               
                            </form> -->
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