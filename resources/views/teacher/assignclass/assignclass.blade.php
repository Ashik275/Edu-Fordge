@extends('admin.app')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Assign table</h4>
            <p class="text-capitalize text-success updatemsg" style="display: none">
            </p>
            <table class="table table-bordered" id="assiugn_table">
                <thead>
                    <tr>
                        <th> # </th>
                        <th> Subject name </th>
                        <th> Class Name </th>
                        <th> Teacher Name </th>
                        <th> Days</th>
                        <th> Time </th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$assigns->isEmpty())
                    @foreach ($assigns as $assign)
                        <tr>
                            <td>{{ $assign->id }}</td>
                            @php
                                $subjectNames = [];
                                foreach (explode(',', $assign->sub_id) as $subjectId) {
                                    $subject = \App\Models\Subjects::find($subjectId);
                                    if ($subject) {
                                        $subjectNames[] = $subject->sub_name;
                                    }
                                }
                            @endphp
                            <td>{{ implode(', ', $subjectNames) }}</td>
                            @php
                                $classNames = [];
                                foreach (explode(',', $assign->class_id) as $classID) {
                                    $class = \App\Models\Classes::find($classID);
                                    if ($class) {
                                        $classNames[] = $class->class_name;
                                    }
                                }
                            @endphp
                            <td>{{ implode(', ', $classNames) }}</td>
                            <td>{{ $assign->teacher->name }}</td>
                            <td>{{ $assign->day }}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $assign->time)->format('g:i A') }}
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
