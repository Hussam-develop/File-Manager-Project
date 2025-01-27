<!DOCTYPE html>
<html>
<head>
    <title>User Actions Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">

        <div class="card card-statistics h-100">
{{--             <h6 class="text-yellow"> User Actions For {{ $user->name }} </h6>
 --}}
            <div class="card-body">
                <div class="col-xl-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">

                            <h2>User Actions Report</h2>
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
                                                {{ $action->id }}
                                            </td>
                                            <td class='text-center'>
                                                {{ $action->file_id }}
                                            </td>
                                            <td class='text-center'>
                                                {{$action->File->name }}
                                            </td>
                                            <td class='text-center'>
                                                {{ $action->action}}
                                            </td>
                                            <td class='text-center'>
                                                {{ Carbon\Carbon::parse($action->created_at)->format('j/n/Y ,g:i a')}}
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

</html>
