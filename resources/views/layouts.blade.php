<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets') }}/loginstyle.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Popup container */
        .popup-container {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Positioning fixed to keep it centered */
            top: 50px;
            /* Adjust to position from the top */
            left: 50px;
            /* Adjust to position from the left */
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black background */
            z-index: 999;
            /* Sit on top of everything */
        }

        /* Popup content */
        .popup-content {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 300px;
            text-align: center;
            position: relative;
        }

        /* Close button */
        .close-btn {
            color: #888;
            font-size: 24px;
            cursor: pointer;
            position: absolute;
            top: 5px;
            /* Adjust distance from top */
            right: 5px;
            /* Adjust distance from right */
        }

        /* Popup Title */
        .popup-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Popup Message */
        .popup-message {
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Popup Action Button */
        .popup-action-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .about-section {
            padding: 50px;
            text-align: center;
            background-color: #f2f2f2;
        }
    </style>
</head>

<body class="antialiased">
    @include('header')
    <div>
        @yield('content')
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script>
    var card = document.getElementById("card");

    function openRegister() {
        card.style.transform = "rotateY(-180deg)";
    }

    function openLogin() {
        card.style.transform = "rotateY(0deg)";
    }


    $('#register').submit(function(e) {
        e.preventDefault();
        var element = $(this);

        $.ajax({
            url: '{{ route('students.store') }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(res) {
                if (res["status"] == false) {
                    // Clear previous errors
                    $('.input-field').siblings('.error-message').remove();
                    // Show errors for each field
                    $.each(res.errors, function(key, value) {
                        $('#' + key).after('<p class="error-message">' + value + '</p>');
                    });
                    setTimeout(function() {
                        $('.error-message').fadeOut('slow');
                    }, 3000);
                }
                if (res["status"] == 'password') {
                    $('.popup-container').fadeIn('slow');
                    $('#pass_msg').html(res["msg"]);
                    // Hide the popup modal after 4 seconds
                    setTimeout(function() {
                        $('.popup-container').fadeOut('slow');
                    }, 4000);
                }
                if (res["status"] == true) {
                    element.trigger('reset');
                    $('.popup-container').fadeIn('slow');
                    $('#pass_msg').html(res["msg"]);
                    // Hide the popup modal after 4 seconds
                    setTimeout(function() {
                        $('.popup-container').fadeOut('slow');
                    }, 4000);
                }

            },
            error: function(jqXHR, exception) {
                console.log('wrong');
            }
        })
    })

    $('#login').submit(function(e) {
        e.preventDefault();
        var element = $(this);

        $.ajax({
            url: '{{ route('students.authenticate') }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(res) {
                if (res["status"] == false) {
                    $('.popup-container').fadeIn('slow');
                    $('#pass_msg').html(res["msg"]);
                    // Hide the popup modal after 4 seconds
                    setTimeout(function() {
                        $('.popup-container').fadeOut('slow');
                    }, 4000);
                }
                if (res["status"] == true) {
                    window.location.href = "{{ route('student.dashboard') }}";
                }

            },
            error: function(jqXHR, exception) {
                console.log('wrong');
            }
        })
    })
</script>

</html>
