@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
طلبات  الرفع المقبولة 
@stop
@endsection
@section('page-header')
<h3 style="color: blue">طلبات  الرفع  المقبولة
</h3>
<!-- breadcrumb -->
@section('PageTitle')
طلبات  الرفع المقبولة 
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
                           
                            <div class="table-responsive">
                                <table  class="table  table-hover table-sm table-bordered p-0"
                                    data-page-length="50" style="text-align: center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان الكتاب</th>
                                            <th>نوعه </th>
                                            <th>الناشر </th>
                                            <th>الصف الدراسي</th>
                                            <th>المادة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($files as $file)
                                            <tr id="{{ $file->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $file->title }}</td>
                                                <td>{{ $file->type }}</td>
                                                <td>{{ $file->teacher->first_name }}</td>
                                                <td>{{ $file->classroom->name }}</td>
                                                <td>{{ $file->subject->name }}</td>
                                                <td>
                                                    <a href="{{route('dashboard.libraries.show',$file->id)}}" class="btn btn-success btn-sm" style="color: rgb(255, 255, 255)" role="button" aria-pressed="true"><i class="fa fa-eye"></i></a>

                                                    <a href="{{route('dashboard.download-pdf',$file->id)}}" title="تحميل الكتاب" class="btn btn-warning btn-sm" role="button" aria-pressed="true"><i class="fa fa-download"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
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
