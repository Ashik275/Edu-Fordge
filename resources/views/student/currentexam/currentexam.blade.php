@extends('admin.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Quiz</h3>
                <h5 class="card-title" id="results"></h5>
                <form id="exam_quiz">
                    @csrf
                    <div class="form-group">
                        @foreach ($quizzes as $quiz)
                            <div class="question">
                                <h3>{{ $quiz->question }}</h3>
                                <ul style="list-style-type: none; padding-left: 0;">
                                    <li>
                                        <label>
                                            <input type="radio" name="answer[{{ $quiz->id }}]" value="A"
                                                @if (isset($results[$quiz->id]) && $results[$quiz->id] === 'A') checked @endif>
                                            A: {{ $quiz->option_a }}
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="answer[{{ $quiz->id }}]" value="B"
                                                @if (isset($results[$quiz->id]) && $results[$quiz->id] === 'B') checked @endif>
                                            B: {{ $quiz->option_b }}
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="answer[{{ $quiz->id }}]" value="C"
                                                @if (isset($results[$quiz->id]) && $results[$quiz->id] === 'C') checked @endif>
                                            C: {{ $quiz->option_c }}
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="answer[{{ $quiz->id }}]" value="D"
                                                @if (isset($results[$quiz->id]) && $results[$quiz->id] === 'D') checked @endif>
                                            D: {{ $quiz->option_d }}
                                        </label>
                                    </li>
                                </ul>
                                @if ($results)
                                    <p>Correct Answer: {{ $quiz->correct_answer }}</p>
                                @endif
                                <input type="hidden" name="correct_answer[{{ $quiz->id }}]"
                                    value="{{ $quiz->correct_answer }}">
                                <input type="hidden" name="quiz_id[]" value="{{ $quiz->id }}">
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary" onclick="submitQuiz()">Submit Answers</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function submitQuiz() {
            // Collect all selected answers
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            let quizForm = document.getElementById('exam_quiz');
            let formData = new FormData(quizForm);
            let score = 0;
            let results = [];

            // Loop through each question
            formData.forEach((value, key) => {
                if (key.startsWith('answer')) {
                    // Extract question ID from input name
                    let questionId = key.replace('answer[', '').replace(']', '');

                    // Find correct answer for this question
                    let correctAnswer = formData.get(`correct_answer[${questionId}]`);

                    // Check if selected answer matches correct answer
                    let selectedAnswer = value;
                    let isCorrect = (selectedAnswer === correctAnswer);

                    // Build result object for this question
                    let result = {
                        exam_id: {{ $exam_id }},
                        questionId: questionId,
                        selectedAnswer: selectedAnswer,
                        correctAnswer: correctAnswer,
                        isCorrect: isCorrect,
                        student_id: {{ $student_id }}

                    };

                    // Push result to results array
                    results.push(result);

                    // Increment score if answer is correct
                    if (isCorrect) {
                        score++;
                    }
                }
            });
            // console.log(resu);
            $.ajax({
                url: '{{ route('stduent-quiz.give-quiz') }}',
                type: 'post',
                data: {
                    _token: csrfToken,
                    results: results
                },
                dataType: 'json',
                success: function(res) {
                    if (res["status"] == true) {
                        $("#results").text(`Your Score is ${res["totalScore"]}`).show();
                        setTimeout(function() {
                            $("#results").fadeOut();
                        }, 5000);
                    }
                }
            })


        }
    </script>
@endpush
