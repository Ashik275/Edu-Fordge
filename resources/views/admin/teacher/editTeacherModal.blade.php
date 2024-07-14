<!-- Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="teacherEditForm">Edit Subject</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="teacherEditForms">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleIputName1">Subject </label>
                                <select class="form-control js-example-basic-multiple-edit" name="subject_id[]"
                                    id="subject_id" multiple>
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        @php
                                            $selected = in_array($subject->id, explode(',', $teacher->subject_id))
                                                ? 'selected'
                                                : '';

                                        @endphp
                                        <option value="{{ $subject->id }}" {{ $selected }}>{{ $subject->sub_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" col-md-6">
                                <label for="exampleIputName1">Class</label>
                                <select class="form-control mutiple-class-edit" id="class_id" name="class_id[]"
                                    multiple>
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        @php
                                            $selected = in_array($class->id, explode(',', $teacher->class_id))
                                                ? 'selected'
                                                : '';

                                        @endphp
                                        <option value="{{ $class->id }}" {{$selected}}>{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3 col-md-6">
                                <label for="exampleIputName1">Name</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ $teacher->name }}">
                            </div>
                            <div class="mt-3 col-md-6">
                                <label for="exampleIputName1">Email</label>
                                <input type="text" class="form-control" name="email" id="email"
                                    value="{{ $teacher->email }}">
                            </div>
                            {{-- <div class="mt-3 col-md-6">
                                <label for="exampleIputName1">Password</label>
                                <input type="text" class="form-control" name="password" id="password"
                                    value="{{ $teacher->password }}">
                            </div> --}}
                        </div>

                    </div>
                    <button type="button" onclick="updateTeacher('{{ $teacher->id }}')"
                        class="btn btn-gradient-primary me-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>

        </div>
    </div>
</div>
@push('script')
    <script></script>
@endpush
