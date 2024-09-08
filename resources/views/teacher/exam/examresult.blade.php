@extends('admin.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.min.css">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Student Result</h4>
                <p class="card-description"> Student Result </p>
                <form class="forms-sample" id="generateQuiz">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleIputName1">Exam</label>
                                <select class="form-control" name="exam_id" id="exam_id">
                                    <option value="">Select Subject</option>
                                    @foreach ($exams as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->exam_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <button type="button" class="btn btn-gradient-primary me-2" onclick="searchResult()">Search</button>
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
                            <th> Exam name </th>
                            <th> Score </th>
                            <th> Student Name </th>
                            <th> Reg No </th>
                        </tr>
                    </thead>
                    <tbody id="exam_result">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>
    <script>
        function searchResult() {
            let exam_id = $("#exam_id").val();
            var url = '{{ route('quiz.quiz-searchdata', 'ID') }}';
            var newURL = url.replace("ID", exam_id);
            $.ajax({
                url: newURL,
                type: "get",
                success: function(res) {
                    console.log(res);
                    $("#exam_result").html(res);
                },
            });
        }
    </script>
@endpush
