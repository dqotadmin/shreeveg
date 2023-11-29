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
            <div class="row g-2">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="tio-category"></i>
                                {{translate('Category Assign')}}
                            </h5>
                        </div>

                        <div class="card-body">
                            <form id="submit-create-role" method="post"
                                action="{{route('admin.warehouse.wh-assign-category')}}"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                @csrf
                                <input type="hidden" name="warehouse_id" value="{{$warehouses->id}}">
                                <div class="d-flex">
                                    <h5 class="input-label m-0 text-capitalize">{{translate('category_permission')}} :
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

                                        @foreach($categories as $category)



                                        <?php
                                        $editRow = Helpers::getWhCategoriesData($category->id,$warehouses->id);
                                        $checked = $status = $margin = $catOrder=''; 
                                            if(!empty($editRow) && $editRow->category_id == $category->id){
                                                $checked ='checked';
                                                $margin = $editRow->margin;
                                                $catOrder = $editRow->category_order;
                                                $status =  $editRow->status;
                                            }
                                        
                                        ?>


                                        <div class="col-md-12">
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
                                                        placeholder="Ex. Order <?php echo $i++; ?>" class="form-control " id="">

                                                    <input type="text" name="margin[]" value="{{$margin}}"
                                                        placeholder="Ex. Margin {{rand(1,5)}}" class="form-control" id="">

                                                   

                                                </div>
                                            </div>
                                        </div>

                                        @endforeach
                                    </div>
                                </div>
                                <div class="btn--container justify-content-end mt-4">
                            <a type="button" href="{{route('admin.warehouse.wh-assign-category-page',[$warehouses['id']])}}" class="btn btn--reset">{{translate('back')}}</a>
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