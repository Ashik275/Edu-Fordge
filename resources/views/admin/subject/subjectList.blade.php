@extends('admin.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Subject table</h4>
                <p class="card-description"> Add Subject
                </p>
                <p class="text-capitalize text-success updatemsg" style="display: none">
                </p>
                <table class="table table-bordered" id="sub_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Subject name </th>
                            <th> Edit </th>
                            <th> Delete </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$subjects->isEmpty())
                            @foreach ($subjects as $subject)
                                <tr>
                                    <td> {{ $subject->id }} </td>
                                    <td> {{ $subject->sub_name }} </td>
                                    <td>
                                        <div class="col-sm-6 col-md-4 col-lg-3"
                                            style="display: inline-block;font-size: 20px;width: 40px;text-align: left;
                            color: #b66dff;"
                                            onclick="subUpdate('{{ $subject->id }}')">
                                            <i class="mdi mdi-border-color"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-sm-6 col-md-4 col-lg-3"
                                            style="display: inline-block;font-size: 20px;width: 40px;text-align: left;
                            color: red;"
                                            onclick="subDelete('{{ $subject->id }}')">
                                            <i class="mdi mdi mdi-delete"></i>
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
        function subUpdate(id) {
            var url = '{{ route('subjects.sub-edit', 'ID') }}';
            var newURL = url.replace("ID", id);
            $.ajax({
                url: newURL,
                type: "get",
                success: function(res) {
                    $("#subjectModal").remove();
                    $("body").append(res);
                    $("#subjectModal").modal("show");
                },
            });
        }

        function updateSubject(id) {
            var form = $('#subjectUpdateForm');
            var element = form.serializeArray();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            form.find(".is-invalid")
                .removeClass("is-invalid")
                .siblings(".invalid-feedback")
                .remove();
            var url = '{{ route('subjects.sub-update', 'ID') }}';
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
                        $(".updatemsg").text("Subject Updated Successfully").show();
                        setTimeout(function() {
                            $(".updatemsg").fadeOut();
                            $("#sub_table").load(" #sub_table > *");
                        }, 5000);
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("wrong");
                },
            });
        }

        function subDelete(id) {
            var url = '{{ route('subjects.sub-delete', 'ID') }}';
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
                            $(".updatemsg").text("Class Deleted Successfully").show();
                            setTimeout(function() {
                                $(".updatemsg").fadeOut();
                                $("#sub_table").load(" #sub_table > *");
                            }, 5000);
                        }
                    },
                });
            }
        }
    </script>
@endpush
