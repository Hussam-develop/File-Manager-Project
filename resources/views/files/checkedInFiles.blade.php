@extends('layouts.master')
@section('title')
{{ __('messages.files') }}
@endsection
<!-- start:breadcrumb -->
@section('breadcrumb')
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


                                @if (session('diff'))
                                <h3>التغييرات بين النسخة القديمة والجديدة:</h3>
                                <pre>
                                    {!! nl2br(e(session('diff'))) !!}
                                </pre>
                                @endif
                                <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                    data-page-length="50" style="text-align: center">
                                    <thead>
                                        <tr>
                                            <th># </th>
                                            <th class='text-center'>{{ __('messages.file name') }} </th>
                                            <th class='text-center'>{{ __('messages.status') }}</th>
                                            <th class='text-center'>{{ __('messages.admin') }}</th>
                                            {{-- <th class='text-center'>{{ __('messages.group name ') }}</th>
                                            --}} <th class='text-center'>{{ __('messages.check status') }}</th>
                                            <th class='text-center'>{{ __('messages.actions') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($files as $file)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration}}
                                            </td>
                                            <td>{{$file->name}}</td>
                                            @if($file->status)
                                            <td style="color: green">
                                                {{ __('messages.active') }}
                                            </td>
                                            @else
                                            <td style="color: rgb(248, 5, 5)">
                                                {{ __('messages.pending') }}

                                            </td>
                                            @endif

                                            <td>{{$file->user->name}}</td>
                                            {{-- <td>{{$file->group->name}}</td> --}}

                                            <td style="width: 80px"> @if ($file->status &&
                                                $file->checkStatus=='free')
                                                <p class="text-success  text-center">
                                                    {{ $file->checkStatus }}
                                                </p>
                                                @elseif ($file->status && $file->checkStatus=='reserved' )
                                                <p class=" text-danger text-center ">reserved</p>
                                                @else
                                                <p class=" text-primary text-center ">-</p>

                                                @endif
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#delete_book{{ $file->id }}" title="checkout">check
                                                    Out</button>


                                                <a href="{{route('admin.dashboard.files.download',$file->id)}}"
                                                    title="download" class="btn btn-warning btn-sm" role="button"
                                                    aria-pressed="true"><i class="fa fa-download"></i></a>

                                            </td>
                                        </tr>
                                        <div class="modal fade" id="delete_book{{$file->id}}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.dashboard.files.checkOut',$file->id) }}"
                                                    method="POST" enctype="multipart/form-data">

                                                    @csrf
                                                    @method('post')
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="background-color: blue">
                                                            <h5 style="font-family: 'Cairo', sans-serif;"
                                                                class="modal-title" id="exampleModalLabel"> Upload
                                                                file</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label for="name"> File </label><br>
                                                            <input type="hidden" name="file_id"
                                                                value="{{ $file->id }}" />
                                                            <input type="file" name="file" class="form-control" />
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">close</button>
                                                            <button type="submit" class="btn btn-success">send</button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        @endforeach


                                </table>

                                {{ $files->links() }}
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
