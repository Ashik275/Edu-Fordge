@extends('admin.app')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Create Meeting</h4>
                <p class="card-description"> Create Meeting </p>
                <form class="forms-sample" id="meetingCreate">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleIputName1">Class Time</label>
                                <input class="form-control" type="datetime-local" name="start_time_input"
                                    id="start_time_input">

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
                    <button type=" submit" class="btn btn-gradient-primary me-2">Create Meeting</button>
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
                <table class="table table-bordered" id="meeting_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Class Time </th>
                            <th> Subject name </th>
                            <th> Class Name </th>
                            <th> Teacher Name </th>
                            <th>Start</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$meetings->isEmpty())
                            @foreach ($meetings as $meeting)
                                <tr>

                                    <td>{{ $meeting->id }}</td>
                                    <td>{{ $meeting->meeting_time }}</td>
                                    <td>{{ $meeting->subject->sub_name }}</td>
                                    <td>{{ $meeting->class->class_name }}</td>
                                    <td>{{ $meeting->teacher->name }}</td>
                                    <td>
                                        @if (!$meeting->is_started)
                                            <a class="btn btn-gradient-info btn-fw"
                                                href="{{ route('teacher.meeting.start', $meeting->id) }}" target="__blank"
                                                onclick="refreshTable()">Start Meeting</a>
                                        @else
                                            <button class="btn btn-gradient-info btn-fw" disabled>Meeting Started</button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="col-sm-6 col-md-4 col-lg-3"
                                            style="display: inline-block;font-size: 20px;width: 40px;text-align: left;color: red;">
                                            <i class="mdi mdi mdi-delete"
                                                onclick="deleteMeeting('{{ $meeting->id }}')"></i>
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
        function refreshTable() {
            setTimeout(function() {
                $("#meeting_table").load(" #meeting_table > *");
            }, 5000);
        }
        $('#meetingCreate').submit(function(e) {
            e.preventDefault();
            var element = $(this);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({
                url: '{{ route('teacher.meeting.live.create') }}',
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
                            $("#meeting_table").load(" #meeting_table > *");
                        }, 5000);
                    }
                }
            })
        })

        function deleteMeeting(id) {
            var url = '{{ route('teacher.meeting.delete', 'ID') }}';
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
                                $("#meeting_table").load(" #meeting_table > *");
                            }, 5000);
                        }
                    },
                });
            }
        }
    </script>
@endpush
