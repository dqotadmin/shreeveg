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
                    {{translate('brokers rate list')}}
                </span>
            </h1>
        </div>
        
        

            <div class="row g-3">
            @foreach($rows as $row)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-uppercase mb-3">{{ $row->brokerDetail->f_name .' '.$row->brokerDetail->l_name}}</h5>
                       
                        <form action="{{route('admin.business-settings.web-app.payment-method-update',['cash_on_delivery'])}}"
                              method="post">
                            @csrf
                            

                                <div class="form-group">
                                    <label class="form-label text--title">
                                        <strong>{{translate('date')}}</strong>:{{ date('d-m-Y H:i A', strtotime($row->date_time))}}
                                    </label>
                                </div>

                                <div class="d-flex flex-wrap mb-4">
                                    
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
                                </div>
                           
                                
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
    </div>

@endsection

@push('script_2')
  

@endpush
