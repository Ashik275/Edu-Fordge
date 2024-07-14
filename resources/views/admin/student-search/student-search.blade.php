@extends('admin.app')
@section('content')
    <style>
        #loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            /* semi-transparent white background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* ensures loader is on top of other content */
        }

        .loader {
            border: 8px solid #f3f3f3;
            /* Light grey */
            border-top: 8px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            /* Spin animation */
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Student Detail</h4>
                <p class="card-description"> Student Detail </p>
                <form class="forms-sample" id="subjectCreateForm">
                    <div class="form-group">
                        <div class=" col-md-6">
                            <label for="exampleIputName1">Class</label>
                            <select class="form-control mutiple-class" id="class_id" name="class_id">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-gradient-primary me-2" onclick="searchStudent()">Search</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card" id="stu">

    </div>
    <div id="loader" style="display: none">
        <div class="loader"></div>
    </div>
@endsection
@push('script')
    <script>
        function searchStudent() {
            $("#loader").show();
            let class_id = $("#class_id").val();
            var url = '{{ route('student.student-searchdata', 'ID') }}';
            var newURL = url.replace("ID", class_id);
            $.ajax({
                url: newURL,
                type: "get",
                success: function(res) {
                    setTimeout(function() {
                        $("#loader").hide();
                        $("#stu").html(res);
                    }, 3000); // 3 seconds delay
                },
            });
        }
    </script>
@endpush
