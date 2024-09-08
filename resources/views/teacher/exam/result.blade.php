@if (!$results->isEmpty())
    @foreach ($results as $result)
        <tr>
            <td> {{ $result->id }} </td>
            <td> {{ $result->exam->exam_name }} </td>
            <td> {{ $result->score }} </td>
            <td> {{ $result->student->name }} </td>
            <td> {{ $result->student->reg_no }} </td>
            {{-- <td> {{ bcrypt($student->password) }}</td> --}}
        </tr>
    @endforeach
@else
    <tr>
        <p>No data found</p>
    </tr>
@endif
