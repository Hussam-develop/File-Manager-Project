@extends('layouts.master')
@section('title')
Actions For {{ $file->name }}
@endsection
<!-- start:breadcrumb -->
@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page">{{ __('messages.files') }}</li>
<li class="breadcrumb-item" aria-current="page">{{ $file->name }}</li>
<li class="breadcrumb-item active" aria-current="page">{{ __('messages.actions') }}</li>

@endsection
<!-- end:breadcrumb -->

@section('page-header')
<br>
<!-- breadcrumb -->
@section('PageTitle')
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
                                <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                    data-page-length="50" style="text-align: center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> {{ __('messages.user name') }} </th>
                                            <th>{{ __('messages.actions') }}</th>
                                            <th>{{ __('messages.added at') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fileActions as $action)
                                        <tr>
                                            <td>
                                                 {{ $action->id }}
                                            </td>
                                            <td class='text-center' >
                                               {{ $action->user->name }}
                                            </td>

                                            <td class='text-center'>{{ $action->action
                                                    }}
                                            </td>
                                            <td class='text-center'>
                                                    {{ Carbon\Carbon::parse($action->created_at)->format('j/n/Y ,g:i a')
                                                    }}
                                              </td>
                                        </tr>


                                        @endforeach
                                </table>
                                {{ $fileActions->links() }}
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
