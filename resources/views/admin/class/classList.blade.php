@extends('admin.app')

@section('content')


    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Class table</h4>
                <p class="card-description"> Add class
                </p>
                <p class="text-capitalize text-success updatemsg" style="display: none">
                </p>
                <table class="table table-bordered" id="class_table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Class name </th>
                            <th> Edit </th>
                            <th> Delete </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$classes->isEmpty())
                            @foreach ($classes as $class)
                                <tr>
                                    <td> {{ $class->id }} </td>
                                    <td> {{ $class->class_name }} </td>
                                    <td>
                                        <div class="col-sm-6 col-md-4 col-lg-3"
                                            style="display: inline-block;font-size: 20px;width: 40px;text-align: left;
                            color: #b66dff;"
                                            onclick="classUpdate('{{ $class->id }}')">
                                            <i class="mdi mdi-border-color"></i>
                                        </div>
                                        <div id="edit-class-route" data-route="{{ route('classes.edit', 'ID') }}"></div>
                                    </td>
                                    <td>
                                        <div class="col-sm-6 col-md-4 col-lg-3"
                                            style="display: inline-block;font-size: 20px;width: 40px;text-align: left;
                            color: red;"
                                            onclick="classDelete('{{ $class->id }}')">
                                            <i class="mdi mdi mdi-delete"></i>
                                        </div>
                                        <div id="delete-class-route" data-route="{{ route('classes.delete', 'ID') }}"></div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function updateClass(id) {
            var form = $('#calssUpdateForm');
            var element = form.serializeArray();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            form.find(".is-invalid")
                .removeClass("is-invalid")
                .siblings(".invalid-feedback")
                .remove();
            var url = '{{ route('classes.update', 'ID') }}';
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
                        $("#exampleModal").modal('hide');
                        $(".updatemsg").text("Class Updated Successfully").show();
                        setTimeout(function() {
                            $(".updatemsg").fadeOut();
                            $("#class_table").load(" #class_table > *");
                        }, 5000);
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("wrong");
                },
            });
        }

        function classDelete(id) {
            var url = '{{ route('classes.delete', 'ID') }}';
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
                                $("#class_table").load(" #class_table > *");
                            }, 5000);
                        }else{
                            $(".updatemsg").text(res['message']).show();
                            setTimeout(function() {
                                $(".updatemsg").fadeOut();
                                $("#class_table").load(" #class_table > *");
                            }, 5000);  
                        }
                    },
                });
            }
        }
    </script>
@endsection
