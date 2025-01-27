@extends('layouts.master')
@section('title')
Group Users
@endsection

<!-- start:breadcrumb -->
@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page">{{ $group->name }}</li>
<li class="breadcrumb-item active" aria-current="page">{{ __('messages.users') }}</li>

@endsection
<!-- end:breadcrumb -->

@section('page-header')

@endsection

@section('PageTitle')
{{ $group->name }} {{ __('messages.users') }}
@stop
<!-- breadcrumb -->
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <div class="col-xl-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <div>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                    data-target="#add-group" title="add group"><i class="fa fa-success fa-plus">{{
                                        __('messages.add user') }}</i>
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
                                            <th> {{ __('messages.user name') }}</th>
                                            <th>{{ __('messages.email') }}</th>
                                            <th>{{ __('messages.added at') }} </th>
                                            <th>{{ __('messages.remove from the group') }}</th>
                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group_Users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>
                                                {{Carbon\Carbon::parse($user->pivot->created_at)->format('j/n/Y ,g:ia')
                                                }}
                                            </td>
                                            <td>

                                                @if($group->admin->id ==auth()->user()->id ||auth()->user()->isAdmin)
                                                @if($user->id!=$group->admin->id || auth()->user()->isAdmin && auth()->user()->id!=$user->id)
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#delete{{ $user->id }}" title="removeUser"><i
                                                        class="fa fa-trash"></i></button>
                                                        @endif
                                                @endif

                                            </td>
                                            <td>
                                                <a href="{{route('admin.dashboard.user.showAction',$user->id)}}"
                                                    class="btn btn-success btn-sm" style="color: rgb(255, 255, 255)"
                                                    role="button" aria-pressed="true">show action</a>
                                            </td>
                                        </tr>

                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="delete{{ $user->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header"
                                                        style="background-color: rgb(253, 216, 92)">
                                                        <h5 style="font-family: 'Cairo', sans-serif;"
                                                            class="modal-title" id="exampleModalLabel">
                                                            Are you sure ? remove "{{$user->name }}" from
                                                            {{$group->name}}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('admin.dashboard.group.removeUser', $group->id) }}"
                                                            method="post">
                                                            {{ method_field('Delete') }}
                                                            @csrf
                                                            <input id="id" type="hidden" name="user_id"
                                                                class="form-control" value="{{ $user->id }}">
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
                                {{ $group_Users->links() }}
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
            <form action="{{route('admin.dashboard.group.addUser',$group->id)}}" method="post">
                @method('Post')
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background-color: blue">
                        <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel"> Assign
                            User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="user"> <span class="text-danger"></span>Username :</label>
                                <select class="custom-select mr-sm-2" name="user_id" required>
                                    <option selected disabled class="form-control"> select user...</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}" class="form-control">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @php
                        $guard='user'
                        @endphp
                        @if (auth('admin')->check())
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
