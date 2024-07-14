@extends('admin.app')

@section('content')
    <style>
        .custom-file-upload {
            position: relative;
            display: inline-block;
        }

        .custom-file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .custom-file-upload label {
            cursor: pointer;
        }
    </style>
    <!-- Modal -->
    <div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" id="passUpdate">
                        <div class="form-group">
                            <label for="exampleIputName1">Password</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Password"
                                name="password" id="password" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary"
                                onclick="changePassModal('{{ $teacher->id }}')">Change Password</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <section style="background-color: #eee;">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body text-center" id="image_div">
                            @if (!is_null($teacherpic))
                                <img src="{{ asset('teacher/' . $teacherpic->image_path) }}" alt="avatar"
                                    class="rounded-circle img-fluid" style="width: 150px;">
                            @else
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp"
                                    alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                            @endif


                            <h5 class="my-3">{{ $teacher->name }}</h5>
                            <p class="text-muted mb-1">{{ $teacher->email }}</p>
                            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center mb-2">
                                {{-- <button type="button" class="btn btn-primary">Follow</button> --}}
                                <div class="custom-file-upload">
                                    <label for="file-upload" class="btn btn-primary">
                                        Choose File
                                    </label>
                                    <input id="file-upload" name="file" type="file">


                                </div>
                                <button type="button" class="btn btn-outline-primary ms-1" onclick="openModal()">Change
                                    Password</button>
                            </div>
                            <div id="error-message" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="card mb-4 mb-lg-0">

                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Full Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ $teacher->name }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ $teacher->email }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        function openModal() {
            $("#changePassModal").modal('show');
        }

        function changePassModal(id) {
            var form = $('#passUpdate');
            var element = form.serializeArray();
            var url = '{{ route('teacher.teacher-pass-edit', 'ID') }}';
            var newURL = url.replace("ID", id);
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                url: newURL,
                type: "post",
                data: element,
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(res) {
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
                    } else {
                        $("#changePassModal").modal('hide');
                    }

                },
            });
        }
        document.getElementById('file-upload').addEventListener('change', function() {
            var fileInput = this;
            var teacher_id = {{ $teacher->id }};
            var errorMessage = document.getElementById('error-message');
            errorMessage.textContent = '';
            if (fileInput.files.length > 0) {
                var file = fileInput.files[0];
                if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
                    errorMessage.textContent = 'Error: Please select a JPG or PNG file.';
                    fileInput.value = '';
                } else {
                    uploadFile(file, teacher_id);
                }
            }
        });

        function uploadFile(file, teacher_id) {
            var formData = new FormData();
            formData.append('file', file);
            formData.append('teacher_id', teacher_id);
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                url: '{{ route('teacher.teacher-image-edit') }}',
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
                    $('#image_div').load('image_div')
                    if (res["status"] == false) {}
                }
            });

        }
    </script>
@endpush
