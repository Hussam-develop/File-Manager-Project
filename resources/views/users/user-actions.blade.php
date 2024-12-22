@extends('layouts.master')
@section('title')
Actions For {{ $user->name }}
@endsection
<!-- start:breadcrumb -->
@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page">{{ __('messages.users') }}</li>
<li class="breadcrumb-item" aria-current="page">{{ $user->name }}</li>
<li class="breadcrumb-item active" aria-current="page">{{ __('messages.actions') }}</li>

@endsection
<!-- end:breadcrumb -->

@section('page-header')
<br>
<!-- breadcrumb -->
@section('PageTitle')
Actions For <b class="text-yellow">{{ $user->name }} </b>
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">

        <div class="card card-statistics h-100">
            <div class="card-body">
                <div class="col-xl-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
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
                                                <b> {{ $action->id }} </b>
                                            </td>
                                            <td class='text-center' style="font-size: 18px;">
                                                <b> {{ $action->file_id }} </b>
                                            </td>
                                            <td class='text-center' style="font-size: 23px;"><span
                                                    class="badge text-black disabled color-palette">{{
                                                    $action->File->name }}</span>
                                            </td>
                                            <td class='text-center' style="font-size: 23px;"><span
                                                    class="badge text-black disabled color-palette">{{ $action->action
                                                    }}</span>
                                            </td>
                                            <td class='text-center' style="font-size: 23px;"><span
                                                    class="badge text-dark  disabled color-palette ">
                                                    {{ Carbon\Carbon::parse($action->created_at)->format('j/n/Y ,g:i a')
                                                    }}
                                                </span></td>
                                        </tr>

                                        <div class="modal fade" id="delete_book{{$user->id}}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{--  --}}" method="post">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 style="font-family: 'Cairo', sans-serif;"
                                                                class="modal-title" id="exampleModalLabel">حذف كتاب</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p> هل انت متأكد من حذف الملف الذي يحمل عنوان <span
                                                                    class="text-danger">{{$user->name}}</span></p>
                                                            <input type="hidden" name="id" value="{{$user->id}}">

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
<!-- row closed -->
@endsection
@section('js')
@toastr_js
@toastr_render
@endsection
