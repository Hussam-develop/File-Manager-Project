@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    قائمة الكتب
@stop
@endsection
@section('page-header')
<h3 style="color: blue"> قائمة الكتب الخاصة بالمعلم</h3>
    <!-- breadcrumb -->
@section('PageTitle')
    قائمة الكتب
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
                                <a href="{{route('dashboard.libraries.create')}}" class="btn btn-success btn-sm" role="button"
                                   aria-pressed="true">اضافة كتاب جديد</a><br><br>
                                <div class="table-responsive">
                                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                           data-page-length="50"
                                           style="text-align: center">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان الكتب</th>
                                            <th>نوعه </th>
                                            <th>الصف الدراسي</th>
                                            <th>المادة</th>
                                            <th>العمليات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($files as $file)
                                            <tr>
                                                <td>{{ $loop->iteration}}</td>
                                                <td>{{$file->title}}</td>
                                                <td>{{$file->type}}</td>
                                                <td>{{$file->classroom->name}}</td>
                                                <td>{{$file->subject->name}}</td>
                                                <td>
                                                    <a href="{{route('dashboard.libraries.show',$file->id)}}" class="btn btn-success btn-sm" style="color: rgb(255, 255, 255)" role="button" aria-pressed="true"><i class="fa fa-eye"></i></a>
                                                    <a href="{{route('dashboard.download-pdf',$file->id)}}" title="تحميل الكتاب" class="btn btn-warning btn-sm" role="button" aria-pressed="true"><i class="fa fa-download"></i></a>
                                                    <a href="{{route('dashboard.libraries.edit',$file->id)}}" class="btn btn-info btn-sm" role="button" aria-pressed="true"><i class="fa fa-edit"></i></a>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete_book{{ $file->id }}" title="حذف"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="delete_book{{$file->id}}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                               <div class="modal-dialog" role="document">
                                                   <form action="{{route('dashboard.libraries.destroy',$file->id)}}" method="post">
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
                                                               <p>  هل انت متأكد من حذف الملف الذي يحمل عنوان <span class="text-danger">{{$file->title}}</span></p>
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