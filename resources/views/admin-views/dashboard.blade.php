@extends('layouts.admin.app')

@section('title', translate('Dashboard'))

@section('content')
    @if(Helpers::module_permission_check(MANAGEMENT_SECTION['dashboard_management']))
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header mb-0 pb-2 border-0">
                <h1 class="page-header-title text-107980">{{ translate('welcome')}}, {{auth('admin')->user()->f_name}}</h1>
                <p class="welcome-msg">{{ translate('welcome_message')}}</p>
            </div>
            <!-- End Page Header -->

           
        @endif
    @endsection