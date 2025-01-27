@extends('layouts.master')
@section('title')
{{ __('messages.files') }}
@endsection
<!-- start:breadcrumb -->
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{ $group->name }}</li>
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
                <div>
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#add-group"
                        title="add group"><i class="fa fa-success fa-plus">{{ __('messages.upload file') }}</i>
                    </button>
                    <br>
                </div>
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
                                                <th class='text-center'>{{ __('messages.file name') }} </th>
                                                <th class='text-center'>{{ __('messages.status') }}</th>
                                                <th class='text-center'>{{ __('messages.admin') }}</th>
                                                <th class='text-center'>{{ __('messages.group name') }}</th>
                                                <th class='text-center'>{{ __('messages.check status') }}</th>
                                                <th class='text-center'>{{ __('messages.actions') }}</th>

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
                                                    {{ __('messages.active') }}
                                                </td>
                                                @else
                                                <td style="color: rgb(248, 5, 5)">
                                                    {{ __('messages.pending') }}

                                                </td>
                                                @endif

                                                <td>{{$file->user->name}}</td>
                                                <td>{{$file->group->name}}</td>

                                                <td style="width: 80px"> @if ($file->status &&
                                                    $file->checkStatus=='free')
                                                   {{--  <a href="javascript:void(0)" id="checkin-btn-{{ $file->id }}"
                                                        class="btn btn-primary btn-sm checkin"
                                                        data-id="{{ $file->id }}">
                                                        Check-in
                                                    </a> --}}
                                                    <a href="{{ route('admin.dashboard.files.checkIn',$file->id) }}" id="checkin-btn-{{ $file->id }}"
                                                        class="btn btn-primary btn-sm"
                                                        data-id="{{ $file->id }}" onclick="disableButton(this)">
                                                        Check-in
                                                    </a>
                                                    @elseif ($file->status && $file->checkStatus=='reserved' )
                                                    <a href="" class="btn btn-danger btn-sm disabled ">reserved</a>
                                                    @else
                                                    <p class=" text-primary text-center ">-</p>

                                                    @endif
                                                </td>
                                                <div id="message-{{ $file->id }}" class="text-success text-center"
                                                    style="width:300px;height:30px;background-color:rgb(165, 231, 9): none;">
                                                </div>

                                                <td>
                                                    <a href="{{route('admin.dashboard.files.actions',$file->id)}}"
                                                        class="btn btn-info btn-sm" style="color: rgb(255, 255, 255)"
                                                        role="button" aria-pressed="true">Tracking </a>

                                                    <a href="{{route('admin.dashboard.files.previousVersions',$file->id)}}"
                                                        class="btn btn-success btn-sm" style="color: rgb(255, 255, 255)"
                                                        role="button" aria-pressed="true">Previous Files </a>

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

                                            @method('POST')
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
                        <input type="hidden" name="group_id" value="{{ $group->id }}" />
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
<script>
function disableButton(link) {
        // تعطيل الرابط
        $(link).addClass('disabled');
        $(link).text('reserved'); // تغيير النص لإظهار حالة الضغط
        $(link).attr('onclick', 'return false;'); // منع الضغط المتكرر

        // يمكنك هنا إضافة أي عملية أخرى مثل إرسال طلب AJAX
    }
     $('.checkinold').on('click',function(){
        var fileId=$(this).data('id');
         $.ajax({

            url:'{{ route('admin.dashboard.files.checkIn',':fileId') }}'.replace(':fileId', fileId), // Use the fileId dynamically,
            type: 'Post',
            data: {
                _token: '{{ csrf_token() }}', // Include CSRF token for security
            },
            success: function(data) {
                // Update the UI based on the response
                $('#checkin-btn-'+ fileId).replaceWith("<p class='text-danger text-center'>reserved</p>");
       // Trigger the file download
            window.location.href ='{{route('admin.dashboard.files.download',':fileId')}}'.replace(':fileId', fileId); // Adjust the download URL
              // Show a success message after a short delay
             // Show a success message after a short delay
             $('#message-' + fileId).text('File download started successfully!').css('color', 'white').show();
                setTimeout(function() {
                    $('#message-' + fileId).fadeOut(); // Optional: fade out the message after a few seconds
                }, 3000); // Adjust the timeout as needed
        },
            error: function(xhr, status, error) {
                // Handle error response
                alert('Error: ' + xhr.responseText);
            }
        });
    });

</script>

@toastr_js
@toastr_render

@endsection
