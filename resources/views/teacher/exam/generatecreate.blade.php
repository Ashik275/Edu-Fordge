@extends('admin.app')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Create Exam</h4>
                <p class="card-description"> Create Exam </p>
                <form class="forms-sample" id="generateQuiz">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleIputName1">Language</label>
                                <select class="form-control" id="questionlanguage" name="questionlanguage">
                                    <option value="">Select Language</option>
                                    <option value="English">English</option>
                                    <option value="Bengali">Bengali</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Exam</label>
                                <select class="form-control" name="exam_id" id="exam_id">
                                    <option value="">Select Subject</option>
                                    @foreach ($exams as $exam)
                                        <option value="{{ $exam->id }}" data-subject-id="{{ $exam->subject_id }}">
                                            {{ $exam->exam_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="sheet">
                                <label for="subject_id">Lecture Sheet</label>
                                <select class="form-control" name="subject_id" id="subject_id">
                                    <option value="">Select Subject</option>
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                            </div>
                            <div class="col-md-6" style="display: none" id="text">
                                <label for="exampleIputName1">Content</label>
                                <textarea class="form-control" name="content" id="content" cols="30" rows="10"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Difficulty</label>
                                <select class="form-control" name="difficulty" id="difficulty">
                                    <option value="">Select Difficulty</option>
                                    <option value="Easy">Easy</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Hard">Hard</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Number of Question</label>
                                <input class="form-control" type="text" name="no_ques" id="no_ques">
                            </div>

                        </div>

                    </div>
                    <button type=" submit" class="btn btn-gradient-primary me-2">Generate Quiz</button>
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
                                    <td>{{ $exam->exam_duration/60 }} Minute</td>
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
        $('#generateQuiz').submit(function(e) {
            e.preventDefault();
            var element = $(this);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // console.log(element.serializeArray());
            $.ajax({
                url: '{{ route('quiz.upload-quiz') }}',
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
                            window.location.href = "{{ route('exam.exam-create') }}";
                        }, 5000);
                    }
                }
            })
        })


        $('#questionlanguage').on('change', function() {
            const selectedValue = $(this).val();
            if (selectedValue == 'English') {
                $("#sheet").css('display', 'block')
                $("#text").css('display', 'none')
            } else {
                $("#text").css('display', 'block')
                $("#sheet").css('display', 'none')
            }
        });

        // $('#exam_id').change(function() {
        //     var subjectId = $(this).find('option:selected').data('subject-id');
        //     if (subjectId) {
        //         alert(subjectId);
        //         var url = '{{ route('quiz.quiz-materials', 'ID') }}';
        //         var newURL = url.replace("ID", subjectId);
        //         $.ajax({
        //             url:newURL,
        //             method: 'GET',
        //             success: function(data) {
        //                 console.log(data);
        //                 var options = '<option value="">Select Subject</option>';
        //                 $.each(data, function(index, material) {
        //                     options += '<option value="' + material.file + '">' + material
        //                         .title + '</option>';
        //                 });
        //                 $('#subject_id').html(options);
        //             }
        //         });
        //     } else {
        //         $('#subject_id').html('<option value="">Select Subject</option>');
        //     }
        // });
    </script>
@endpush
