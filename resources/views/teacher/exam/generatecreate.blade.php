@extends('admin.app')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Create Exam</h4>
                <p class="card-description"> Create Exam </p>
                <form class="forms-sample" id="examCreate">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleIputName1">Lecture Sheet</label>
                                <select class="form-control" name="subject_id" id="subject_id">
                                    <option value="">Select Subject</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->file }}">{{ $material->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                   
                        </div>

                    </div>
                    <button type=" submit" class="btn btn-gradient-primary me-2">Create Exam</button>
                    <p class="updatemsg" style="text-align: right;color:green;font-weight:bold"></p>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Exams</h4>
                <p class="text-capitalize text-success updatemsg" style="display: none">
                </p>
                <table class="table table-bordered" id="exam_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Exam Time </th>
                            <th> Subject name </th>
                            <th> Class Name </th>
                            <th>Duration</th>
                            <th>Add Question</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$exams->isEmpty())
                            @foreach ($exams as $exam)
                                <tr>

                                    <td>{{ $exam->id }}</td>
                                    <td>{{ $exam->exam_date }}</td>
                                    <td>{{ $exam->subject->sub_name }}</td>
                                    <td>{{ $exam->class->class_name }}</td>
                                    <td>{{ $exam->exam_duration }} Hour</td>
                                    <td>
                                      dtyh
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

