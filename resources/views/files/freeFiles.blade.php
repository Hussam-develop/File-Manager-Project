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
                                                {{-- <th class='text-center'>{{ __('messages.group name ') }}</th>
                                                --}} <th class='text-center'>{{ __('messages.check status') }}</th>
                                                <th class='text-center'>{{ __('messages.actions') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($free_files as $file)
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
                                                    <a href="javascript:void(0)" id="checkin-btn-{{ $file->id }}"
                                                        class="btn btn-primary btn-sm checkin"
                                                        data-id="{{ $file->id }}">
                                                        Check-in
                                                    </a>
                                                    @elseif ($file->status && $file->checkStatus=='reserved' )
                                                    <p class=" text-danger text-center ">reserved</p>
                                                    @else
                                                    <p class=" text-primary text-center ">-</p>

                                                    @endif
                                                </td>
                                                <div id="message-{{ $file->id }}" class="text-success text-center"
                                                    style="width:300px;height:30px;background-color:rgb(165, 231, 9): none;">
                                                </div>

                                                <td>


                                                    <a href="{{route('admin.dashboard.files.download',$file->id)}}"
                                                        title="download" class="btn btn-warning btn-sm" role="button"
                                                        aria-pressed="true"><i class="fa fa-download"></i></a>

                                                </td>
                                            </tr>

                                            @endforeach
                                            @method('POST')
                                            @if($free_files->where('checkStatus','free')->count() > 0)

                                            <button type="submit" class="btn btn-primary col-md-12 ">
                                                Check in selected Files </button>
                                            @endif

                                </form>

                                </table>

                                {{ $free_files->links() }}
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
<script>
    $('.checkin').on('click',function(){
        var fileId=$(this).data('id');
         $.ajax({

            url:'{{ route('admin.dashboard.files.checkIn',':fileId') }}'.replace(':fileId', fileId), // Use the fileId dynamically,
            type: 'get',
            data: {
                _token: '{{ csrf_token() }}', // Include CSRF token for security
            },
            success: function(response) {
                // Update the UI based on the response
                $('#checkin-btn-'+ fileId).replaceWith("<p class='text-danger text-center'>reserved</p>");
       // Trigger the file download
            window.location.href = '{{route('admin.dashboard.files.download',':fileId')}}'.replace(':fileId', fileId); // Adjust the download URL
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
