<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('admin-assets') }}/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('admin-assets') }}/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="{{ asset('admin-assets') }}/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('admin-assets') }}/assets/images/favicon.ico" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container-scroller">
        @include('admin.includes.header')
        <div class="container-fluid page-body-wrapper">
            @include('admin.includes.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                @include('admin.includes.footer')
            </div>
        </div>
    </div>


    <!-- plugins:js -->
    <script src="{{ asset('admin-assets') }}/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('admin-assets') }}/assets/vendors/chart.js/Chart.min.js"></script>
    <script src="{{ asset('admin-assets') }}/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('admin-assets') }}/assets/js/off-canvas.js"></script>
    <script src="{{ asset('admin-assets') }}/assets/js/hoverable-collapse.js"></script>
    <script src="{{ asset('admin-assets') }}/assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('admin-assets') }}/assets/js/dashboard.js"></script>
    <script src="{{ asset('admin-assets') }}/assets/js/todolist.js"></script>
    <script src="{{ asset('admin-assets') }}/assets/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- End custom js for this page -->
    @stack('script')
</body>

</html>
