@extends('admin.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Class Schedule</h4>
                <p class="text-capitalize text-success updatemsg" style="display: none">
                </p>
                <table class="table table-bordered" id="assiugn_table">
                    <thead>
                        <tr>
                            <th> Exam name </th>
                            <th> Subject Name </th>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$get_exams->isEmpty())
                            @foreach ($get_exams as $exam)
                                <tr>
                                    <td>{{ $exam->exam_name }}</td>
                                    <td>{{ $exam->subject->sub_name }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary"
                                            onclick="giveExam('{{ $exam->id }}')">Give Exam</button>
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
        function giveExam(examId) {
            var url = '{{ route('student.currentexamquesiton', 'ID') }}';
            var newURL = url.replace("ID", examId);
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

        function submitExam(examId) {
            const form = document.getElementById('submitExam');
            const formData = new FormData(form);
            const selectedAnswers = {};
            const correctAnswers = {};
            let score = 0;

            // Extract selected answers from FormData
            for (const [key, value] of formData.entries()) {
                if (key.startsWith('answer')) {
                    const questionId = key.match(/\d+/)[0]; // Extract the question ID
                    selectedAnswers[questionId] = value;
                }
            }

            // Extract correct answers from hidden inputs
            document.querySelectorAll('input[type="hidden"]').forEach(input => {
                const questionId = input.id.split('_')[2]; // Extract the question ID
                const correctAnswer = input.dataset.correctAnswer;
                correctAnswers[questionId] = correctAnswer;
            });

            // Calculate the score
            for (const questionId in selectedAnswers) {
                if (selectedAnswers[questionId] === correctAnswers[questionId]) {
                    score += 1; // Increment score for each correct answer
                }
            }
            let quiz_id = $("#quiz_id").val();
            // Prepare the data to be sent
            const data = {
                answers: selectedAnswers,
                examId: examId,
                quiz_id: quiz_id,
                score: score,
                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token for security
            };

            // Send the data using jQuery AJAX
            $.ajax({
                url: '{{ route('stduent-quiz.give-quiz') }}',
                type: 'POST',
                data: data,
                success: function(response) {
                    console.log('Success:', response);
                    // Optionally, handle the server response, e.g., show a success message
                    alert('Quiz submitted successfully! Your score is ' + score);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    // Optionally, handle errors
                    alert('An error occurred while submitting the quiz.');
                }
            });
        }
    </script>
@endpush
