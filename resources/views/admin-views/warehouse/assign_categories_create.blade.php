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
                                <div class="row align-items-end g-4" style="margin-top: 20px;">
                                    <div class="col-sm-12" id="category_box">
                                        <div class="form-group">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('Select Parent Category')}}</label>
                                            <select name="category_id[]" id="category_id"    class="form-control chosen-select" multiple >
                                                 <option value="" disabled>---{{translate('Select Category')}}---</option>
                                                <?php echo $options; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{ $options}}
                                <?php echo '<br>'; ?>
                                <?php echo '<br>'; ?>
                                <?php echo '<br>'; 
                               $wh_assign_category=  json_decode($wh_assign_categories,true);
                            //    print_r($wh_assign_category);
                               foreach($wh_assign_category as $wh){
                                echo ($wh['category_id']);
                                echo '<br>';
                               }
                                ?>
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