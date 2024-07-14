@extends('admin.app')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Subject Creation</h4>
                <p class="card-description"> Subject Creation </p>
                <form class="forms-sample" id="subjectCreateForm">
                    <div class="form-group">
                        <label for="exampleIputName1">Subject</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Name" name="sub_name"
                            id="sub_name">
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $("#subjectCreateForm").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var element = form.serializeArray();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            form.find(".is-invalid")
                .removeClass("is-invalid")
                .siblings(".invalid-feedback")
                .remove();

            $.ajax({
                url: '{{ route('subjects.sub-store') }}',
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
                        window.location.href = "{{ route('subjects.sub-index') }}";
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("wrong");
                },
            });
        });

       
    </script>
@endpush
