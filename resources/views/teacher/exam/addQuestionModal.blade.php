<!-- Modal -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="subjectModalLabel">Edit Subject</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="addQuestion">
                    <div class="form-group">

                        <div class="col-md-12">
                            <label for="exampleIputName1">Number Of Qestion </label>
                            <input type="text" class="form-control" name="num_of_question" id="num_of_question" onblur="generateQueston()">
                            <input type="hidden" class="form-control" name="exan_id" id="exan_id"  value="{{ $exam_id }}">

                        </div>
                        {{-- <div class="row mt-2">
                            <div class=" col-md-12">
                                <label for="exampleIputName1">Question</label>
                                <input type="text" class="form-control" name="question" id="question">
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Option A</label>
                                <input type="text" class="form-control" name="option_a" id="option_a" \>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Option B</label>
                                <input type="text" class="form-control" name="option_b" id="option_b" \>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Option C</label>
                                <input type="text" class="form-control" name="option_c" id="option_c" \>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Option D</label>
                                <input type="text" class="form-control" name="option_d" id="option_d" \>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Correct Answer</label>
                                <input type="text" class="form-control" name="correct_answer" id="correct_answer" \>
                            </div>
                        </div> --}}
                        <div id="questionsSection" >
                            <!-- This section will be dynamically populated -->
                        </div>

                    </div>
                    <button type="button" class="btn btn-gradient-primary me-2" onclick="addQuiz()">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>


        </div>
    </div>
</div>

@push('script')
<script>

            
</script>
@endpush