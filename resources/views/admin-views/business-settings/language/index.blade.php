@extends('layouts.admin.app')

@section('title', translate('language'))


@section('content')
<div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/lang.png')}}" class="w--24" alt="">
            </span>
            <span>
                {{translate('system settings')}}
            </span>
        </h1>
        <ul class="nav nav-tabs border-0 mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="{{route('admin.business-settings.web-app.system-setup.language.index')}}">
                    {{ translate('Language Setup') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.business-settings.web-app.system-setup.app_setting')}}">
                    {{ translate('App Settings') }}
                </a>
            </li>
             {{--<li class="nav-item">
                <a class="nav-link" href="{{route('admin.business-settings.web-app.system-setup.firebase_message_config_index')}}">
                    {{ translate('Firebase Configuration') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.business-settings.web-app.system-setup.db-index')}}">
                    {{ translate('Clean Database') }}
                </a>
            </li> --}}
        </ul>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert--danger alert-danger mb-3" role="alert">
                <div class="d-flex">
                    <span class="alert--icon"><i class="tio-info"></i></span>
                    <strong class="text--title word-nobreak">{{translate('note')}} : </strong>
                    <div class="w-0 flex-grow align-self-center pl-2">
                        {{translate('changing_some_settings_will_take_time_to_show_effect_please_clear_session_or_wait_for_60_minutes_else_browse_from_incognito_mode')}}
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{route('admin.business-settings.web-app.system-setup.language.add-new')}}" method="post"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">{{translate('Language Name')}}</label>
                                    <input type="text" class="form-control" id="recipient-name" name="name" placeholder="{{translate('Ex : English')}}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">{{translate('Country Code')}} </label>
                                    <select class="form-control js-select2-custom w-100" name="code">
                                        <option value="en-IN">English (India)</option>
                                        <option value="hi">Hindi - हिन्दी</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="display table table-borderless table-hover min-w-980px"
                               style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{translate('SL')}}</th>
                                <th class="border-0">{{translate('name')}}</th>
                                <th class="border-0">{{translate('Code')}}</th>
                                <th class="border-0 text-center">{{translate('status')}}</th>
                                <th class="border-0 text-center">{{translate('default')}} {{translate('status')}}</th>
                                <th class="border-0 w-260px text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($language = App\CentralLogics\Helpers::get_business_settings('language'))
                            @if(isset($language) && array_key_exists('code', $language[0]))
                                @foreach($language as $key =>$data)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$data['name']}}
                                        </td>
                                        <td>{{$data['code']}}</td>
                                        <td>
                                            <label class="toggle-switch toggle-switch-sm">
                                                <input type="checkbox"
                                                       onclick="updateStatus('{{route('admin.business-settings.web-app.system-setup.language.update-status')}}','{{$data['code']}}','{{$data['default']}}')"
                                                       class="toggle-switch-input" {{$data['status']==1?'checked':''}}>
                                                <span class="toggle-switch-label text mx-auto">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="toggle-switch toggle-switch-sm">
                                                <input type="checkbox"
                                                       onclick="window.location.href ='{{route('admin.business-settings.web-app.system-setup.language.update-default-status', ['code'=>$data['code']])}}'"
                                                       class="toggle-switch-input" {{ ((array_key_exists('default', $data) && $data['default']==true) ? 'checked': ((array_key_exists('default', $data) && $data['default']==false) ? '' : 'disabled')) }}>
                                                <span class="toggle-switch-label text mx-auto">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn--container justify-content-end">
                                                <a class="btn--primary-2 btn-outline-primary-2 btn-35px"
                                                    href="{{route('admin.business-settings.web-app.system-setup.language.translate',[$data['code']])}}">{{translate('translated data')}}</a>
                                                @if($data['code']!='en')
                                                    <a class="action-btn btn--primary btn-outline-primary" data-toggle="modal"
                                                        data-target="#lang-modal-update-{{$data['code']}}" href="javascript:void(0)"><i class="tio-edit"></i></a>
                                                    @if($data['default'] != true)
                                                        <button class="action-btn btn--danger btn-outline-danger" id="delete"
                                                                onclick="delete_language('{{ route('admin.business-settings.web-app.system-setup.language.delete',[$data['code']]) }}')"><i class="tio-delete-outlined"></i></button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($language) && array_key_exists('code', $language[0]))
        @foreach($language as $key =>$data)
            <div class="modal fade" id="lang-modal-update-{{$data['code']}}" tabindex="-1" role="dialog"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header pb-3 border-bottom">
                            <h5 class="modal-title"
                                id="exampleModalLabel">{{translate('update_language')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('admin.business-settings.web-app.system-setup.language.update')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">{{translate('language')}} </label>
                                            <input type="text" class="form-control" value="{{$data['name']}}" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="message-text"
                                                   class="col-form-label">{{translate('country_code')}}</label>
                                            <select class="form-control js-select2-custom" name="code" style="width: 100%">
                                                <option value="{{$data['code']}}">{{$data['code']}}</option>
                                            </select>
                                        </div>
                                    </div>
{{--                                        <div class="col-6">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="col-form-label">{{translate('direction')}}:</label>--}}
{{--                                                <select class="form-control" name="direction">--}}
{{--                                                    <option value="ltr" {{isset($data['direction'])?$data['direction']=='ltr'?'selected':'':''}}>LTR--}}
{{--                                                    </option>--}}
{{--                                                    <option value="rtl" {{isset($data['direction'])?$data['direction']=='rtl'?'selected':'':''}}>RTL--}}
{{--                                                    </option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                </div>
                                <input type="hidden" class="form-control" value="{{$data['status']}}" name="status">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn--reset"
                                        data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit"
                                        class="btn btn--primary">{{translate('update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection

@push('script_2')
    <!-- Page level plugins -->
{{--    <script src="{{asset('public/assets/admin')}}/vendor/datatables/jquery.dataTables.min.js"></script>--}}
{{--    <script src="{{asset('public/assets/admin')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>--}}

    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        // $(document).ready(function () {
        //     $('#dataTable').DataTable();
        // });

        function updateStatus(route, code, default_status) {
            if(code == 'en') {
                Swal.fire({
                    title: '{{ translate("You can not change the status of English language") }}',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Okay',
                    denyButtonText: `cancel`,
                }).then((result) => {
                    location.reload();
                })
            } else if(default_status == 1) {
                Swal.fire({
                    title: '{{ translate("You can not change the status of default language") }}',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Okay',
                    denyButtonText: `cancel`,
                }).then((result) => {
                    location.reload();
                })
            } else {
                $.get({
                    url: route,
                    data: {
                        code: code,
                    },
                    success: function (data) {
                        location.reload();
                    }
                });
            }
        }
    </script>

    <script>
        function delete_language(route) {
            Swal.fire({
                title: '{{translate('Are you sure to delete this')}}?',
                text: "{{translate('You will not be able to revert this')}}!",
                showCancelButton: true,
                confirmButtonColor: 'primary',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{translate('Yes, delete it')}}!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = route;
                }
            })
        }
    </script>
@endpush
