@extends('layouts.master')
@section('title')
{{ __('messages.files') }}
@endsection
<!-- start:breadcrumb -->
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{ $mainfile->name }}</li>
<li class="breadcrumb-item active" aria-current="page">{{ __('messages.files') }}</li>

@endsection
<!-- end:breadcrumb -->

<!-- start:page-header -->
@section('page-header')

@endsection

@section('PageTitle')
{{ __('messages.files') }}
@stop
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">

                <div class="col-xl-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">

                            <div class="table-responsive">
                                {{-- multi check in Form --}}


                                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                        data-page-length="50" style="text-align: center">
                                        <thead>
                                            <tr>
                                                <th># </th>
                                                <th class='text-center'>{{ __('messages.file name') }} </th>
                                                <th class='text-center'>{{ __('messages.admin') }}</th>
                                                <th class='text-center'>{{ __('messages.actions') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($previousFiles as $file)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration}}
                                                </td>
                                                <td>{{$file->backup_file}}</td>



                                                <td>{{$mainfile->user->name}}</td>
                                                <td>
                                                    <a href="{{route('admin.dashboard.files.restoreFile',$file->id)}}"
                                                        class="btn btn-primary btn-sm" style="color: rgb(255, 255, 255)"
                                                        role="button" aria-pressed="true">Restore</a>
                                                        <a href="{{route('admin.dashboard.files.downloadOldVersion',$file->id)}}"
                                                            title="download" class="btn btn-warning btn-sm" role="button"
                                                            aria-pressed="true"><i class="fa fa-download"></i></a>

                                                </td>
                                            </tr>

                                            @endforeach
                                </table>

                                {{ $previousFiles->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- row closed -->
@endsection
@section('js')

@toastr_js
@toastr_render

@endsection
