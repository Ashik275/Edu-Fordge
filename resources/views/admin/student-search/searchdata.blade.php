<div class="card">
    <div class="card-body">
        <h4 class="card-title">Student table</h4>
        <table class="table table-bordered" id="sub_table">
            <thead>
                <tr>
                    <th> # </th>
                    <th> Student name </th>
                    <th> Registration No </th>
                    <th> Email </th>
                    {{-- <th> Password </th> --}}
                </tr>
            </thead>
            <tbody>
                @if (!$students->isEmpty())
                    @foreach ($students as $student)
                        <tr>
                            <td> {{ $student->id }} </td>
                            <td> {{ $student->name }} </td>
                            <td> {{ $student->reg_no }} </td>
                            <td> {{ $student->email }} </td>
                            {{-- <td> {{ bcrypt($student->password) }}</td> --}}
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <p>No data found</p>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
