@extends('layouts.admin.app')

@section('title', translate('Add new group'))

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
                    {{translate('group setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.business-settings.groups.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}</label>
                                        <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="{{ translate('name') }}" maxlength="255" required>
                                    </div>
                                </div>
                                
                                
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label class="input-label"
                                                for="{{translate('description')}}">{{translate('description')}}</label>
                                            <textarea name="description" class="form-control h--172px " id="" required></textarea>
                                        </div>
                                       
                                    </div>
                                    
                                    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex flex-column justify-content-center h-100">
                                        <h5 class="text-center mb-3 text--title text-capitalize">
                                            {{translate('group')}} {{translate('image')}}
                                            <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                                        </h5>
                                        <label class="upload--vertical">
                                            <input type="file" name="image" id="customFileEg1" class="" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" hidden>
                                            <img id="viewer" src="{{asset('public/assets/admin/img/upload-vertical.png')}}" alt="banner image"/>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <!-- Header -->
            <div class="card-header border-0">
                <div class="card--header justify-content-between max--sm-grow">
                    <h5 class="card-title">{{translate('group List')}} <span class="badge badge-soft-secondary">{{ $rows->total() }}</span></h5>
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
                        <th class="border-0">{{translate('image')}}</th>
                        <th class="border-0">{{translate('name')}}</th>
                        <th class="border-0">{{translate('description')}}</th>
                        <th class="text-center border-0">{{translate('status')}}</th>
                        <th class="text-center border-0">{{translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($rows as $key=>$row)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                <div>
                                    <img class="img-vertical-150" src="{{asset('storage/app/public/groups')}}/{{$row['image']}}" onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'">
                                </div>
                            </td>
                            <td>
                                <span class="d-block font-size-sm text-body text-trim-25">
                                    {{$row['name']}}
                                </span>
                            </td>
                            <td>
                                {{ substr($row['description'], 0, 100)}}
                            </td>
                            <td>
                                <label class="toggle-switch my-0">
                                    <input type="checkbox"
                                           onclick="status_change_alert('{{ route('admin.business-settings.groups.status', [$row->id, $row->status ? 0 : 1]) }}', '{{ $row->status? translate('you_want_to_disable_this_group'): translate('you_want_to_active_this_group') }}', event)"
                                           class="toggle-switch-input" id="stocksCheckbox{{ $row->id }}"
                                        {{ $row->status ? 'checked' : '' }}>
                                    <span class="toggle-switch-label mx-auto text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <!-- Dropdown -->
                                <div class="btn--container justify-content-center">
                                    <a class="action-btn"
                                       href="{{route('admin.business-settings.groups.edit',[$row['id']])}}">
                                        <i class="tio-edit"></i></a>
                                    <a class="action-btn btn--danger btn-outline-danger" href="javascript:"
                                       onclick="form_alert('banner-{{$row['id']}}','{{ translate("Want to delete this") }}')">
                                        <i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.business-settings.groups.delete',[$row['id']])}}"
                                      method="post" id="banner-{{$row['id']}}">
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
