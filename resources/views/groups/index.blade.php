@extends('layouts.master')
@section('title')
{{ __('messages.groups') }}

@endsection
<!-- start:breadcrumb -->
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{ __('messages.groups') }}
</li>
@endsection
<!-- end:breadcrumb -->

@section('page-header')

@endsection

@section('PageTitle')
{{ __('messages.groups') }}
@stop
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        @php
        $guard='user'
        @endphp
        @if(auth()->user()->isAdmin)
        @php
        $guard='admin'
        @endphp
        @endif
        <div class="card card-statistics h-100">
            <div class="card-body">
                <div class="col-xl-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <div>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                    data-target="#add-group" title="add group"><i class="fa fa-success fa-plus">
                                        {{ __('messages.add group') }}</i>
                                </button>
                                <br>
                            </div>
                            {{-- <a href="{{route('dashboard.libraries.create')}}" class="btn btn-success btn-sm"
                                role="button" aria-pressed="true">اضافة كتاب جديد</a><br><br> --}}
                            <div class="table-responsive">
                                <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                    data-page-length="50" style="text-align: center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> {{ __('messages.group name') }}</th>
                                            <th> {{ __('messages.admin') }}</th>

                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($groups as $group)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$group->name}}</td>
                                            <td>{{$group->admin->name}}</td>

                                            <td>
                                                @if(Auth::user()->id==$group->admin->id)

                                                  <a href="{{route('admin.dashboard.group.users',$group->id)}}"
                                                    class="btn btn-success btn-sm" style="color: rgb(255, 255, 255)"
                                                    role="button" aria-pressed="true">{{ __('messages.users') }}</a>

                                                @endif
                                                <a href="{{route('admin.dashboard.group.files',$group->id)}}"
                                                    class="btn btn-secondary btn-sm" style="color: rgb(255, 255, 255)"
                                                    role="button" aria-pressed="true">{{ __('messages.files') }}</a>
                                                {{--<a href="{{route('dashboard.download-pdf',$file->id)}}"
                                                    title="تحميل الكتاب" class="btn btn-warning btn-sm" role="button"
                                                    aria-pressed="true"><i class="fa fa-download"></i></a>
                                                <a href="{{route('dashboard.libraries.edit',$file->id)}}"
                                                    class="btn btn-info btn-sm" role="button" aria-pressed="true"><i
                                                        class="fa fa-edit"></i></a>
                                                --}}
                                                @if ($group->admin_id==auth()->user()->id || auth()->user()->isAdmin)
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#delete{{ $group->id }}" title="delete"><i
                                                        class="fa fa-trash"></i></button>
                                                @endif

                                            </td>
                                        </tr>

                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="delete{{ $group->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header"
                                                        style="background-color: rgb(253, 216, 92)">
                                                        <h5 style="font-family: 'Cairo', sans-serif;"
                                                            class="modal-title" id="exampleModalLabel">
                                                            Are you sure ? delete "{{$group->name }}"
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('admin.dashboard.groups.destroy', $group->id) }}"
                                                            method="post">
                                                            {{ method_field('Delete') }}
                                                            @csrf
                                                            <input id="id" type="hidden" name="user_id"
                                                                class="form-control" value="{{ $group->id }}">
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">close</button>
                                                                <button type="submit"
                                                                    class="btn btn-danger">send</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                </table>
                                {{ $groups->links() }}
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
            <form action="{{route('admin.dashboard.groups.store')}}" method="post">
                @method('Post')
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background-color: blue">
                        <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel"> add
                            group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="name">: Group name </label><br>
                        <input type="text" name="name" value="{{old('name',request('name'))}}" required
                            class="form-control" />
                        @php
                        $guard='user'
                        @endphp
                        @if (auth()->user()->isAdmin)
                        @php
                        $guard='admin'
                        @endphp
                        @endif
                        <input type="hidden" name="guard" value="{{$guard}}" />
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
