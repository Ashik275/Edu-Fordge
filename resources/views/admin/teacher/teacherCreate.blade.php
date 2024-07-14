@extends('admin.app')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Teacher Creation</h4>
                <p class="card-description"> Teacher Creation </p>
                <form class="forms-sample" id="teacherCreateForm">
                    <div class="form-group">
                        <div class="row">
                            <div class=" col-md-6">
                                <label for="exampleIputName1">Class</label>
                                <select class="form-control mutiple-class" id="class_id" name="class_id[]" multiple>
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleIputName1">Subject</label>
                                <select class="form-control js-example-basic-multiple" name="subject_id[]" id="subject_id"
                                    multiple>
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->sub_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3 col-md-6">
                                <label for="exampleIputName1">Name</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="mt-3 col-md-6">
                                <label for="exampleIputName1">Email</label>
                                <input type="text" class="form-control" name="email" id="email">
                            </div>
                            <div class="mt-3 col-md-6">
                                <label for="exampleIputName1">Password</label>
                                <input type="text" class="form-control" name="password" id="password">
                            </div>
                            <div class="mt-3 col-md-6">
                                <label for="exampleIputName1">Nid No</label>
                                <input type="text" class="form-control" name="nid" id="nid">
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
                <h4 class="card-title">Teacher table</h4>
                <p class="text-capitalize text-success updatemsg" style="display: none">
                </p>
                <table class="table table-bordered" id="teacher_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Subject name </th>
                            <th> Class Name </th>
                            <th> Teacher Name </th>
                            <th> Teacher Email </th>
                            <th> Edit </th>
                            <th> Delete </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$teachers->isEmpty())
                            @foreach ($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->id }}</td>
                                    @php
                                        $subjectNames = [];
                                        foreach (explode(',', $teacher->subject_id) as $subjectId) {
                                            $subject = \App\Models\Subjects::find($subjectId);
                                            if ($subject) {
                                                $subjectNames[] = $subject->sub_name;
                                            }
                                        }
                                    @endphp
                                    <td>{{ implode(', ', $subjectNames) }}</td>
                                    @php
                                        $classNames = [];
                                        foreach (explode(',', $teacher->class_id) as $classID) {
                                            $class = \App\Models\Classes::find($classID);
                                            if ($class) {
                                                $classNames[] = $class->class_name;
                                            }
                                        }
                                    @endphp
                                    <td>{{ implode(', ', $classNames) }}</td>
                                    <td>{{ $teacher->name }}</td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>
                                        <div class="col-sm-6 col-md-4 col-lg-3"
                                            style="display: inline-block;font-size: 20px;width: 40px;text-align: left;color: #b66dff;">
                                            <i class="mdi mdi-border-color"
                                                onclick="teacherUpdateModal('{{ $teacher->id }}')"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-sm-6 col-md-4 col-lg-3"
                                            style="display: inline-block;font-size: 20px;width: 40px;text-align: left;color: red;">
                                            <i class="mdi mdi mdi-delete"
                                                onclick="deleteTeacher('{{ $teacher->id }}')"></i>
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
            $('.js-example-basic-multiple').select2();
            $('.mutiple-class').select2();
        });
        $("#teacherCreateForm").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var element = form.serializeArray();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            form.find(".is-invalid")
                .removeClass("is-invalid")
                .siblings(".invalid-feedback")
                .remove();

            $.ajax({
                url: '{{ route('teachers.teacher-store') }}',
                type: "post",
                data: element,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(res) {
                    console.log(res);
                    if (res["status"] == false) {
                        // Iterate through errors object and display error message for each field
                        $.each(res.errors, function(field, messages) {
                            var inputField = form.find('[name="' + field + '"]');
                            var errorMessage =
                                '<span class="invalid-feedback" role="alert">' +
                                messages.join("<br>") +
                                "</span>";
                            inputField.addClass("is-invalid").after(errorMessage);
                        });

                        // Hide error messages after 3 seconds
                        setTimeout(function() {
                            form.find(".is-invalid")
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
                            form[0].reset();
                            $(".updatemsg").fadeOut();
                            $("#teacher_table").load(" #teacher_table > *");
                        }, 5000);
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("wrong");
                },
            });
        });

        function teacherUpdateModal(id) {
            var url = '{{ route('teachers.teacher-edit', 'ID') }}';
            var newURL = url.replace("ID", id);
            $.ajax({
                url: newURL,
                type: "get",
                success: function(res) {
                    console.log(res);
                    $("#subjectModal").remove();
                    $("body").append(res);
                    $("#subjectModal").modal("show");
                    // Initialize Select2 for the dropdowns

                },
            });

        }

        function updateTeacher(id) {
            var form = $('#teacherEditForms');
            var element = form.serializeArray();
            console.log(element);
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            form.find(".is-invalid")
                .removeClass("is-invalid")
                .siblings(".invalid-feedback")
                .remove();

            var url = '{{ route('teachers.teacher-update', 'ID') }}';
            var newURL = url.replace("ID", id);

            $.ajax({
                url: newURL,
                type: "post",
                data: element,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(res) {
                    console.log(res);
                    if (res["status"] == false) {
                        // Iterate through errors object and display error message for each field
                        $.each(res.errors, function(field, messages) {
                            var inputField = form.find('[name="' + field + '"]');
                            var errorMessage =
                                '<span class="invalid-feedback" role="alert">' +
                                messages.join("<br>") +
                                "</span>";
                            inputField.addClass("is-invalid").after(errorMessage);
                        });

                        // Hide error messages after 3 seconds
                        setTimeout(function() {
                            form.find(".is-invalid")
                                .removeClass("is-invalid")
                                .siblings(".invalid-feedback")
                                .fadeOut(300, function() {
                                    $(this).remove();
                                });
                        }, 3000);
                    }
                    if (res["status"] == true) {
                        $("#subjectModal").modal('hide');
                        $(".updatemsg").text(res["message"]).show();
                        setTimeout(function() {
                            $(".updatemsg").fadeOut();
                            $("#teacher_table").load(" #teacher_table > *");
                        }, 5000);
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("wrong");
                },
            });
        }

        function deleteTeacher(id) {
            var url = '{{ route('teachers.teacher-delete', 'ID') }}';
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
                                $("#teacher_table").load(" #teacher_table > *");
                            }, 5000);
                        }
                    },
                });
            }
        }
    </script>
@endpush
