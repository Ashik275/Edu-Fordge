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
                                <label for="exampleIputName1">Exam Time</label>
                                <input class="form-control" type="datetime-local" name="start_time_input"
                                    id="start_time_input">

                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Exam Duration</label>
                                <input class="form-control" type=text" name="exam_duration" id="exam_duration">

                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Topic</label>
                                <input class="form-control" type="text" name="topic" id="topic">
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Subject</label>
                                <select class="form-control" name="subject_id" id="subject_id">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->sub_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" col-md-6">
                                <label for="exampleIputName1">Class</label>
                                <select class="form-control" id="class_id" name="class_id">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
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
                                        <button class="btn btn-gradient-info btn-fw"
                                            onclick="addQuestions('{{ $exam->id }}')">Add
                                            Question</button>
                                        <button class="btn btn-gradient-info btn-fw"
                                            onclick="viewQuestions('{{ $exam->id }}')">View
                                            Question</button>
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
@push('script')
    <script>
        $('#examCreate').submit(function(e) {
            e.preventDefault();
            var element = $(this);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // console.log(element.serializeArray());
            $.ajax({
                url: '{{ route('exam.exam-add') }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in request headers
                },
                success: function(res) {
                    console.log(res);
                    if (res["status"] == false) {
                        // Iterate through errors object and display error message for each field
                        $.each(res.errors, function(field, messages) {
                            var inputField = element.find('[name="' + field + '"]');
                            var errorMessage =
                                '<span class="invalid-feedback" role="alert">' +
                                messages.join("<br>") +
                                "</span>";
                            inputField.addClass("is-invalid").after(errorMessage);
                        });

                        // Hide error messages after 3 seconds
                        setTimeout(function() {
                            element.find(".is-invalid")
                                .removeClass("is-invalid")
                                .siblings(".invalid-feedback")
                                .fadeOut(300, function() {
                                    $(this).remove();
                                });
                        }, 3000);
                    }
                    if (res["status"] == true) {
                        $(".updatemsg").text(res["message"]).show();
                        setTimeout(function() {
                            $(".updatemsg").fadeOut();
                            $("#exam_table").load(" #exam_table > *");
                        }, 5000);
                    }
                }
            })
        })

        function addQuestions(id) {
            var url = '{{ route('exam.add-quiz', 'ID') }}';
            var newURL = url.replace("ID", id);
            $.ajax({
                url: newURL,
                type: "get",
                success: function(res) {
                    $("#questionModal").remove();
                    $("body").append(res);
                    $("#questionModal").modal("show");
                },
            });
        }
        function viewQuestions(id) {
            var url = '{{ route('exam.add-view', 'ID') }}';
            var newURL = url.replace("ID", id);
            $.ajax({
                url: newURL,
                type: "get",
                success: function(res) {
                    console.log(res);
                    $("#viewQuestion").remove();
                    $("body").append(res);
                    $("#viewQuestion").modal("show");
                },
            });
        }

        function generateQueston() {
            let num_of_question = $('#num_of_question').val();
            var questionsSection = $('#questionsSection');
            questionsSection.html(''); // Clear previous content

            for (var i = 1; i <= num_of_question; i++) {
                var questionInputs = `
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="question_${i}">Question ${i}</label>
                        <input type="text" class="form-control" name="question_${i}" id="question_${i}">
                    </div>
                    <div class="col-md-6">
                        <label for="option_a_${i}">Option A</label>
                        <input type="text" class="form-control" name="option_a_${i}" id="option_a_${i}">
                    </div>
                    <div class="col-md-6">
                        <label for="option_b_${i}">Option B</label>
                        <input type="text" class="form-control" name="option_b_${i}" id="option_b_${i}">
                    </div>
                    <div class="col-md-6">
                        <label for="option_c_${i}">Option C</label>
                        <input type="text" class="form-control" name="option_c_${i}" id="option_c_${i}">
                    </div>
                    <div class="col-md-6">
                        <label for="option_d_${i}">Option D</label>
                        <input type="text" class="form-control" name="option_d_${i}" id="option_d_${i}">
                    </div>
                    <div class="col-md-6">
                        <label for="correct_answer_${i}">Correct Answer</label>
                        <input type="text" class="form-control" name="correct_answer_${i}" id="correct_answer_${i}">
                    </div>
                </div>
            `;
                questionsSection.append(questionInputs);
            }
        }

        function addQuiz(){
            var questions = $('#addQuestion');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // console.log(element.serializeArray());
            $.ajax({
                url: '{{ route('quiz.quiz-add') }}',
                type: 'post',
                data: questions.serializeArray(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in request headers
                },
                success: function(res) {
                    console.log(res);
                    if (res["status"] == false) {
                        // Iterate through errors object and display error message for each field
                        $.each(res.errors, function(field, messages) {
                            var inputField = questions.find('[name="' + field + '"]');
                            var errorMessage =
                                '<span class="invalid-feedback" role="alert">' +
                                messages.join("<br>") +
                                "</span>";
                            inputField.addClass("is-invalid").after(errorMessage);
                        });

                        // Hide error messages after 3 seconds
                        setTimeout(function() {
                            questions.find(".is-invalid")
                                .removeClass("is-invalid")
                                .siblings(".invalid-feedback")
                                .fadeOut(300, function() {
                                    $(this).remove();
                                });
                        }, 3000);
                    }
                    if (res["status"] == true) {
                        $("#questionModal").modal("hide");
                        $(".updatemsg").text(res["message"]).show();
                        setTimeout(function() {
                            $(".updatemsg").fadeOut();
                        }, 5000);
                    }
                }
            })
        }
    </script>
@endpush
