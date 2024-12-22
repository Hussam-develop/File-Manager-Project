@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    اضافة كتاب جديد
@stop
@endsection
@section('page-header')
    <!-- breadcrumb -->
@section('PageTitle')
    اضافة كتاب جديد
@stop
<!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">

                    @if(session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ session()->get('error') }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="col-xs-12">
                        <div class="col-md-12">
                            <br>
                            <form action="{{route('dashboard.libraries.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">

                                    <div class="col">
                                        <label for="title">اسم الكتاب</label>
                                        <input type="text" name="title" class="form-control">
                                    </div>

                                </div>
                                <div class="form-row">

                                    <div class="col">
                                        <label for="type">نوع المرفق</label>
                                        <select name="type" class="custom-select mr-sm-2">
                                            <option value="book">كتاب</option>
                                            <option value="document">مرفق</option>
                                        </select>
                                      
                                    </div>

                                </div>
                                <br>

                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="classroom_id">الصف الدراسي : <span class="text-danger">*</span></label>
                                            <select class="custom-select mr-sm-2" name="classroom_id">
                                                <option selected disabled>اختر الصف ...</option>
                                                @foreach($classrooms as $classroom)
                                                    <option  value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            <label for="subject_id">المادة : <span class="text-danger">*</span></label>
                                            <select class="custom-select mr-sm-2" name="subject_id">

                                            </select>
                                        </div>
                                    </div>

                                   

                                </div><br>



                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="file_url">الملف / الكتاب : <span class="text-danger">*</span></label>
                                            <input type="file" accept="application/pdf" name="file_url" required>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" type="submit">حفظ البيانات</button>
                            </form>
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
    <script>
        $(document).ready(function () {
            $('select[name="classroom_id"]').on('change', function () {
                var classroom_id = $(this).val();
              
                if (classroom_id) {
    
    
                   
                
                    $.ajax({
    
                        url: "{{ URL::to('dashboard/class_subjects') }}/" + classroom_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="subject_id"]').empty();
                            $('select[name="subject_id"]').append('<option selected disabled value="">' +' ..اختر مادة'+ '</option>');
                            $.each(data, function (index, subject) {
                              
                                $('select[name="subject_id"]').append('<option value="' + subject.id + '">' + subject.name  + '</option>');
                            });
    
                        },
                    });
                }
    
                else {
                    console.log('AJAX load did not work');
                }
            });
        });
    </script>
    
@endsection