
<div class="modal fade" id="viewQuestion" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="subjectModalLabel">{{ $examName }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="submitExam">
                    <div class="form-group">
                        @foreach ($quizes as $quiz)
                            <div class="question">
                                <h3>{{ $quiz->question }}</h3>
                                <ul style="list-style-type: none; padding-left: 0;">
                                    <li>
                                        <label for="option_a_{{ $quiz->id }}">A. </label>
                                        <input type="radio" name="answer[{{ $quiz->id }}]" value="a"
                                            id="option_a_{{ $quiz->id }}"
                                            {{ isset($previousAnswers[$quiz->id]) && $previousAnswers[$quiz->id] == 'a' ? 'checked' : '' }}
                                            {{ $examTaken ? 'disabled' : '' }}>
                                        {{ $quiz->option_a }}
                                    </li>
                                    <li>
                                        <label for="option_b_{{ $quiz->id }}">B. </label>
                                        <input type="radio" name="answer[{{ $quiz->id }}]" value="b"
                                            id="option_b_{{ $quiz->id }}"
                                            {{ isset($previousAnswers[$quiz->id]) && $previousAnswers[$quiz->id] == 'b' ? 'checked' : '' }}
                                            {{ $examTaken ? 'disabled' : '' }}>
                                        {{ $quiz->option_b }}
                                    </li>
                                    <li>
                                        <label for="option_c_{{ $quiz->id }}">C. </label>
                                        <input type="radio" name="answer[{{ $quiz->id }}]" value="c"
                                            id="option_c_{{ $quiz->id }}"
                                            {{ isset($previousAnswers[$quiz->id]) && $previousAnswers[$quiz->id] == 'c' ? 'checked' : '' }}
                                            {{ $examTaken ? 'disabled' : '' }}>
                                        {{ $quiz->option_c }}
                                    </li>
                                    <li>
                                        <label for="option_d_{{ $quiz->id }}">D. </label>
                                        <input type="radio" name="answer[{{ $quiz->id }}]" value="d"
                                            id="option_d_{{ $quiz->id }}"
                                            {{ isset($previousAnswers[$quiz->id]) && $previousAnswers[$quiz->id] == 'd' ? 'checked' : '' }}
                                            {{ $examTaken ? 'disabled' : '' }}>
                                        {{ $quiz->option_d }}
                                    </li>
                                </ul>
                                @if ($examTaken)
                                    <!-- Display correct answer as text if the exam has been taken -->
                                    <p>Correct Answer: {{ $quiz->correct_answer }}</p>
                                @else
                                    <!-- Hide the correct answer if the exam has not been taken -->
                                    <input type="hidden" id="correct_answer_{{ $quiz->id }}"
                                        value="{{ $quiz->correct_answer }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-gradient-primary me-2"
                        onclick="submitExam('{{ $exam_id }}')" {{ $examTaken ? 'disabled' : '' }}>Submit</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
