@extends('admin.app')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Materials</h4>
            <p class="text-capitalize text-success updatemsg" style="display: none">
            </p>
            <table class="table table-bordered" id="teacher_table">
                <thead>
                    <tr>
                        <th> # </th>
                        <th> Subject name </th>
                        <th> Class Name </th>
                        <th> Teacher Name </th>
                        <th> Title </th>
                        <th> File</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$materials->isEmpty())
                        @foreach ($materials as $material)
                            <tr>
                                <td>{{ $material->id }}</td>
                                <td>{{ $material->subject->sub_name }}</td>
                                <td>{{ $material->class->class_name }}</td>
                                <td>{{ $material->teacher->name }}</td>
                                <td>{{ $material->title }}</td>
                                <td>
                                    <a class="btn btn-gradient-info btn-fw" href="{{ asset('/materials/' . $material->file) }}"
                                        target="_blank">View File</a>
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
