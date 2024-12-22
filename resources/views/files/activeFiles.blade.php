@extends('layouts.master')
@section('title')
{{ __('messages.active files') }}
@endsection
<!-- start:breadcrumb -->
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{ __('messages.files') }}</li>
<li class="breadcrumb-item active" aria-current="page">{{ __('messages.active files') }}</li>

@endsection
<!-- end:breadcrumb -->

<!-- start:page-header -->
@section('page-header')

@endsection

@section('PageTitle')
{{ __('messages.active files') }}
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

                                <form id="multiCheckInForm" action="{{ route('admin.dashboard.files.multiCheckIn')}}"
                                    method="POST">
                                    @csrf
                                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                        data-page-length="50" style="text-align: center">
                                        <thead>
                                            <tr>
                                                <th># </th>
                                                <th class='text-center'>Name </th>
                                                <th class='text-center'>Status</th>
                                                <th class='text-center'>Admin</th>
                                                <th class='text-center'>Group Name</th>
                                                <th class='text-center'>Check Status</th>
                                                <th class='text-center'>options</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($files as $file)
                                            <tr>
                                                <td> @if($file->status && $file->checkStatus=='free')
                                                    <input type="checkbox" name="fileIds[]" value="{{$file->id}}" />
                                                    @endif
                                                    {{ $loop->iteration}}
                                                </td>
                                                <td>{{$file->name}}</td>
                                                @if($file->status)
                                                <td style="color: green">
                                                    active
                                                </td>
                                                @else
                                                <td style="color: rgb(248, 5, 5)">
                                                    pending
                                                </td>
                                                @endif

                                                <td>{{$file->user->name}}</td>
                                                <td>{{$file->group->name}}</td>

                                                <td style="width: 80px"> @if ($file->status &&
                                                    $file->checkStatus=='free')
                                                    <p class="text-success  text-center">
                                                        {{  $file->checkStatus }}
                                                    </p>
                                                    @elseif ($file->status && $file->checkStatus=='reserved' )
                                                    <p class=" text-danger text-center ">reserved</p>
                                                    @else
                                                    <p class=" text-primary text-center ">-</p>

                                                    @endif
                                                </td>

                                                <td>
                                                    <a href="{{--route('admin.dashboard.files.showAction',$user->id)--}}"
                                                        class="btn btn-success btn-sm" style="color: rgb(255, 255, 255)"
                                                        role="button" aria-pressed="true">show action</a>
                                                    <a href="{{route('admin.dashboard.files.download',$file->id)}}"
                                                        title="download" class="btn btn-warning btn-sm" role="button"
                                                        aria-pressed="true"><i class="fa fa-download"></i></a>
                                                    {{-- <a href="{{route('dashboard.libraries.edit',$file->id)}}"
                                                        class="btn btn-info btn-sm" role="button" aria-pressed="true"><i
                                                            class="fa fa-edit"></i></a>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="modal" data-target="#delete_book{{ $file->id }}"
                                                        title="حذف"><i class="fa fa-trash"></i></button> --}}
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="delete_book{{$file->id}}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{route('admin.dashboard.files.destroy',$file->id)}}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 style="font-family: 'Cairo', sans-serif;"
                                                                    class="modal-title" id="exampleModalLabel">حذف كتاب
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p> هل انت متأكد من حذف الملف الذي يحمل عنوان <span
                                                                        class="text-danger">{{$file->name}}</span></p>
                                                                <input type="hidden" name="id" value="{{$file->id}}">

                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">إغلاق</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger">حذف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            @endforeach

                                            @if($files->where('checkStatus','free')->count() > 0)

                                            <button type="submit" class="btn btn-primary col-md-12 ">
                                                Check in selected Files </button>
                                                @endif

                                </form>

                                </table>

                                {{ $files->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-group" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('admin.dashboard.files.store')}}" method="post" enctype="multipart/form-data">
                @method('Post')
                @csrf
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
                        <input type="file" name="file" class="form-control" />
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">close</button>
                        <button type="submit" class="btn btn-success">send</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')

@toastr_js
@toastr_render

@endsection
