@extends('layouts.admin.app')

@section('title', translate('Banner List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/banner.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('banner')}} {{translate('list')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header border-0">
                <div class="card--header justify-content-between max--sm-grow">
                    <h5 class="card-title">{{translate('Banner List')}} <span class="badge badge-soft-secondary"> </span></h5>
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input type="search" name="search" class="form-control"
                                placeholder="{{translate('Search_by_ID_or_name')}}" aria-label="Search"
                                   value=" " required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text">
                                    {{translate('search')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0">{{translate('#')}}</th>
                        <th class="border-0">{{translate('banner image')}}</th>
                        <th class="border-0">{{translate('title')}}</th>
                        <th class="border-0">{{translate('banner type')}}</th>
                        <th class="text-center border-0">{{translate('status')}}</th>
                        <th class="text-center border-0">{{translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
               
                    </tbody>
                </table>
                <table>
                    <tfoot>
                     
                    </tfoot>
                     
                </table>

            </div>

            <!-- End Table -->
        </div>
        <!-- End Card -->
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
@endpush
