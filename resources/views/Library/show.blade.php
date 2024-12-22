@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
  الملف
@stop
@endsection
@section('page-header')
<h3 style="color: blue">    الملف</h3>
    <!-- breadcrumb -->
@section('PageTitle')
     الملف
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
                                <iframe src="{{ $file->getImageUrl() }}" width="800px" height="800px" ></iframe>
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