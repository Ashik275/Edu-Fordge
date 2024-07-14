@extends('admin.app')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Materials Creation</h4>
                <p class="card-description"> Materials Creation </p>
                <form class="forms-sample" id="materialsCreateForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="row">
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
                            <div class="mt-3 col-md-6">
                                <label for="exampleIputName1">Title</label>
                                <input type="text" class="form-control" name="title" id="title">
                            </div>
                            <div class="mt-3 col-md-6">
                                <label for="exampleIputName1">File</label>
                                <input type="file" class="form-control" name="file" id="file">
                            </div>
                        </div>

                    </div>
                    <button type=" submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    <p class="updatemsg" style="text-align: right;color:green;font-weight:bold"></p>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Materials</h4>
                <p class="text-capitalize text-success updatemsg" style="display: none">
                </p>
                <table class="table table-bordered" id="teacher_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Subject name </th>
                            <th> Class Name </th>
                            <th> Teacher Name </th>
                            <th> Title </th>
                            <th> File</th>
                            <th> Delete </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$materials->isEmpty())
                            @foreach ($materials as $material)
                                <tr>
                                    <td>{{ $material->id }}</td>
                                    <td>{{ $material->subject->sub_name ?? ' ' }}</td>
                                    <td>{{ $material->class->class_name ?? ' ' }}</td>
                                    <td>{{ $material->teacher->name }}</td>
                                    <td>{{ $material->title }}</td>
                                    <td>
                                        <a class="btn btn-gradient-info btn-fw" href="{{ asset('/materials/' . $material->file) }}"
                                            target="_blank">View File</a>
                                    </td>
                                    </td>

                                    <td>
                                        <div class="col-sm-6 col-md-4 col-lg-3"
                                            style="display: inline-block;font-size: 20px;width: 40px;text-align: left;color: red;">
                                            <i class="mdi mdi mdi-delete"
                                                onclick="deleteMaterial('{{ $material->id }}')"></i>
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
        $("#materialsCreateForm").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(form[0]);
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            form.find(".is-invalid")
                .removeClass("is-invalid")
                .siblings(".invalid-feedback")
                .remove();

            $.ajax({
                url: '{{ route('teacher.teacher-material-store') }}',
                type: "post",
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content type
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
                        form[0].reset();
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
        });

        function deleteMaterial(id) {
            var url = '{{ route('teacher.teacher-material-delete', 'ID') }}';
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
