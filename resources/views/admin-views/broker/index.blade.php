@extends('layouts.admin.app')

@section('title', translate('Add new banner'))

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
                    {{translate('rate list setup')}}
                </span>
            </h1>
        </div>
        
        <div class="row g-2">
            <div class="col-sm-12 col-lg-12">
                <div class="btn--container justify-content-end m-2">
                    <a type="button"  href="{{route('admin.broker-rate-list.create')}}" class="btn btn--primary">{{translate('Add New')}}</a>
                </div>
                    
                </div>
            </div>

        <div class="card">
            <!-- Header -->
            <div class="card-header border-0">
                <div class="card--header justify-content-between max--sm-grow">
                    <h5 class="card-title">{{translate('Rate List')}} <span class="badge badge-soft-secondary">{{ $rows->total() }}</span></h5>
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input type="search" name="search" class="form-control"
                                   placeholder="{{translate('Search_by_ID_or_name')}}" aria-label="Search"
                                   value="{{$search}}" required autocomplete="off">
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
                        <th class="border-0">{{translate('title')}}</th>
                        <th class="border-0">{{translate('date')}}</th>
                        <th class="border-0">{{translate('time')}}</th>
                        <th class="text-center border-0">{{translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($rows as $key=>$row)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                <span class="d-block font-size-sm text-body text-trim-25">
                                    {{$row->title }}
                                </span>
                            </td>
                            <td>
                                {{ date('d-m-Y',strtotime($row->date_time))}}
                            </td>
                            <td>
                                {{ date('H:i A',strtotime($row->date_time))}}
                            </td>
                            
                            <td>
                                <!-- Dropdown -->
                                <div class="btn--container justify-content-center">
                                    
                                    <a class="action-btn btn--danger btn-outline-danger" href="javascript:"
                                       onclick="form_alert('rate_list-{{$row->id}}','{{ translate("Want to delete this") }}')">
                                        <i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.broker-rate-list.destroy',[$row->id])}}"
                                      method="post" id="rate_list-{{$row->id}}">
                                    @csrf @method('delete')
                                </form>
                                <!-- End Dropdown -->
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <table>
                    <tfoot>
                    {!! $rows->links() !!}
                    </tfoot>
                </table>

            </div>
            @if(count($rows) == 0)
                <div class="text-center p-4">
                    <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
                    <p class="mb-0">{{translate('No_data_to_show')}}</p>
                </div>
            @endif
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
  

@endpush
