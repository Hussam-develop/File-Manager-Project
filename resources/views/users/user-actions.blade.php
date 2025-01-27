@extends('layouts.master')
@section('title')
User Actions
@endsection
<!-- start:breadcrumb -->
@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.dashboard.users') }}">{{ __('messages.users')
        }}</a></li>
<li class="breadcrumb-item" aria-current="page">{{ $user->name }}</li>
<li class="breadcrumb-item active" aria-current="page">{{ __('messages.actions') }}</li>

@endsection
<!-- end:breadcrumb -->

@section('page-header')
<br>
<!-- breadcrumb -->
@section('PageTitle')
User Actions For <b class="text-yellow">{{ $user->name }} </b>
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        {{-- start of export & import operation --}}

        <div class="row mb-3">
            <div class="col-md-1">
                <form action="{{route('admin.userActions.export', ['id'=>$user->id,'format' => 'csv'])}}" method="GET">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.export to csv') }}</button>
                </form>

            </div>
            <div class="col-md-1">
                <form action="{{route('admin.userActions.export', ['id'=>$user->id,'format' => 'pdf']) }}" method="GET">
                    <button type="submit" class="btn btn-danger btn-sm"> {{ __('messages.export to pdf') }}</button>
                </form>
            </div>

        </div>
        {{-- end of export & import operation --}}
        <div class="card card-statistics h-100">
            <h6 class="text-yellow"> User Actions For {{ $user->name }} </h6>

            <div class="card-body">
                <div class="col-xl-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <button type="button" class="btn btn-success btn-sm btn-plus" data-toggle="modal"
                                data-target="#importCsv" title="import CSV"> <i class="fa fa-success fa-plus">
                                    {{ __('messages.import from csv') }}</i></button>

                            {{-- <a href="{{route('dashboard.libraries.create')}}" class="btn btn-success btn-sm"
                                role="button" aria-pressed="true">اضافة كتاب جديد</a><br><br> --}}
                            <div class="table-responsive">
                                <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                    data-page-length="50" style="text-align: center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> File Id </th>
                                            <th> File Name </th>
                                            <th>Action</th>
                                            <th>Created at</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userActions as $action)
                                        <tr>
                                            <td>
                                                {{ $action->id }}
                                            </td>
                                            <td class='text-center'>
                                                {{ $action->file_id }}
                                            </td>
                                            <td class='text-center'>
                                                {{$action->File->name }}
                                            </td>
                                            <td class='text-center'>
                                                {{ $action->action}}
                                            </td>
                                            <td class='text-center'>
                                                {{ Carbon\Carbon::parse($action->created_at)->format('j/n/Y ,g:i a')}}
                                            </td>
                                        </tr>


                                        @endforeach
                                </table>
                                {{ $userActions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="importCsv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('admin.userActions.import',$user->id)}}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('post')
            <div class="modal-content">
                <div class="modal-header" style="background-color: blue">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel"> Upload
                        file</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="name"> File </label><br>
                    <input type="file" name="csv_file" accept=".csv" required class="form-control-file">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">close</button>
                    <button type="submit" class="btn btn-success">send</button>
                </div>

            </div>
        </form>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')
@toastr_js
@toastr_render
@endsection
