@extends('admin.app')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Assign Class</h4>
                <p class="card-description"> Assign Class </p>
                <form class="forms-sample" id="assign_class">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleIputName1">Teacher</label>
                                <select class="form-control js-example-basic-multiple" name="teacher_id" id="teacher_id"
                                    onchange="classsubject()">
                                    <option value="">Select Subject</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" col-md-6">
                                <label for="exampleIputName1">Class</label>
                                <select class="form-control mutiple-class" id="class_id" name="class_id">
                                    <option value="">Select Class</option>
                                </select>
                            </div>
                            <div class="col-md-6  mt-3">
                                <label for="exampleIputName1">Subject</label>
                                <select class="form-control js-example-basic-multiple" name="subject_id" id="subject_id">
                                    <option value="">Select Subject</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="day">Day</label>
                                <select class="form-control mutiple-day" name="day[]" id="day" multiple>
                                    <option value="">Select Day</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="time">Time</label>
                                <select class="form-control" name="time" id="time">
                                    <option value="">Select Time</option>
                                    <!-- Loop through hours from 10 AM to 8 PM -->
                                    @for ($hour = 10; $hour <= 20; $hour++)
                                        <!-- Format hour as HH:00 -->
                                        @php
                                            $time = sprintf('%02d:00', $hour);
                                            $timeWithAMPM = date('h A', strtotime($time));

                                        @endphp
                                        <!-- Display formatted time in dropdown -->
                                        <option value="{{ $time }}">{{ $timeWithAMPM }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                    </div>
                    <button type=" submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Assign table</h4>
                <p class="text-capitalize text-success updatemsg" style="display: none">
                </p>
                <table class="table table-bordered" id="assiugn_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Subject name </th>
                            <th> Class Name </th>
                            <th> Teacher Name </th>
                            <th> Days</th>
                            <th> Time </th>
                            <th> Delete </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$assigns->isEmpty())
                            @foreach ($assigns as $assign)
                                <tr>
                                    <td>{{ $assign->id }}</td>
                                    @php
                                        $subjectNames = [];
                                        foreach (explode(',', $assign->sub_id) as $subjectId) {
                                            $subject = \App\Models\Subjects::find($subjectId);
                                            if ($subject) {
                                                $subjectNames[] = $subject->sub_name;
                                            }
                                        }
                                    @endphp
                                    <td>{{ implode(', ', $subjectNames) }}</td>
                                    @php
                                        $classNames = [];
                                        foreach (explode(',', $assign->class_id) as $classID) {
                                            $class = \App\Models\Classes::find($classID);
                                            if ($class) {
                                                $classNames[] = $class->class_name;
                                            }
                                        }
                                    @endphp
                                    <td>{{ implode(', ', $classNames) }}</td>
                                    <td>{{ $assign->teacher->name }}</td>
                                    <td>{{ $assign->day }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $assign->time)->format('g:i A') }}
                                    </td>
                                    <td>
                                        <div class="col-sm-6 col-md-4 col-lg-3"
                                            style="display: inline-block;font-size: 20px;width: 40px;text-align: left;color: red;">
                                            <i class="mdi mdi mdi-delete"
                                                onclick="deleteAssignClass('{{ $assign->id }}')"></i>
                                        </div>
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
        $(document).ready(function() {
            $('.mutiple-day').select2();
        });

        function classsubject() {
            let id = $("#teacher_id").val();
            var url = '{{ route('assignclass.teacher-getsubclass', 'ID') }}';
            var newURL = url.replace("ID", id);
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                url: newURL,
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(res) {
                    console.log(res);
                    // Populate subject select tag
                    $('#subject_id').empty();
                    $('#subject_id').append($('<option>', {
                        value: '',
                        text: 'Select Subject'
                    }));
                    $('#class_id').empty();
                    $('#class_id').append($('<option>', {
                        value: '',
                        text: 'Select Class'
                    }));
                    $.each(res.subjects, function(index, subject) {
                        $('#subject_id').append($('<option>', {
                            value: subject.id,
                            text: subject.sub_name
                        }));
                    });
                    $.each(res.classes, function(index, classItem) {
                        $('#class_id').append($('<option>', {
                            value: classItem.id,
                            text: classItem.class_name
                        }));
                    });
                },
            });
        }
        $("#assign_class").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var element = form.serializeArray();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            form.find(".is-invalid")
                .removeClass("is-invalid")
                .siblings(".invalid-feedback")
                .remove();

            $.ajax({
                url: '{{ route('assignclass.teacher-assignclassstore') }}',
                type: "post",
                data: element,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(res) {
                    console.log(res);
                    if (res["status"] == false) {
                        error(res, form);
                        $(".updatemsg").text(res["message"]).show();
                        setTimeout(function() {
                            form[0].reset();
                            $(".updatemsg").fadeOut();
                            $("#assiugn_table").load(" #assiugn_table > *");
                        }, 5000);
                    }
                    if (res["status"] == true) {
                        $(".updatemsg").text(res["message"]).show();
                        setTimeout(function() {
                            form[0].reset();
                            $(".updatemsg").fadeOut();
                            $("#assiugn_table").load(" #assiugn_table > *");
                        }, 5000);
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("wrong");
                },
            });
        });

        function deleteAssignClass(id) {
            var url = '{{ route('assignclass.teacher-assignclassdelete', 'ID') }}';
            var newURL = url.replace("ID", id);
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            if (confirm("Are you sure you want to delete ?")) {
                $.ajax({
                    url: newURL,
                    type: "delete",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function(res) {
                        console.log(res);
                        if (res["status"] == true) {
                            $(".updatemsg").text(res["message"]).show();
                            setTimeout(function() {
                                $(".updatemsg").fadeOut();
                                $("#assiugn_table").load(" #assiugn_table > *");
                            }, 5000);
                        }
                    },
                });
            }
        }
    </script>
@endpush
