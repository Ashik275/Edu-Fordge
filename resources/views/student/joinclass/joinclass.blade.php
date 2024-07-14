@extends('admin.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Materials</h4>
                <p class="text-capitalize text-success updatemsg" style="display: none">
                </p>
                <table class="table table-bordered" id="meeting_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Subject name </th>
                            <th> Class Name </th>
                            <th> Teacher Name </th>
                            <th>Start</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$meetings->isEmpty())
                            @foreach ($meetings as $meeting)
                                <tr>
                                    <td>{{ $meeting->id }}</td>
                                    <td>{{ $meeting->subject->sub_name }}</td>
                                    <td>{{ $meeting->class->class_name }}</td>
                                    <td>{{ $meeting->teacher->name }}</td>
                                    <td>
                                        <a class="btn btn-gradient-info btn-fw" href="{{ $meeting->link }}" target="__blank"
                                            onclick="refreshTable()">Join Class</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
