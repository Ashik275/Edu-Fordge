@extends('layouts')
@section('content')
<style>
    .carousel {
        background-image: url('{{ asset('assets/images/demo.jpg') }}');
 
    }
</style>
    <div class="container pt-2 pb-2">
        <div class="carousel">
            <h1 class="carousel-heading">Get Started Digital

            </h1>
            <h1 class="carousel-heading">
                Learning

            </h1>
            <p class="carousel-p">
                We Are The Top Performing E-learning Platform
            </p>
            <button class="carousel-btn">
                Get Started
            </button>
        </div>
    </div>
    <div class="container pt-2 pb-2">
        <div class="sec-1">
            <img class="image" src="{{ asset('assets') }}/images/demo-2.jpg" alt="">
            <div class="details">
                <h1 class="details-heading">Explore The EduForge Institute</h1>
                <p>Our institute is the market-leading e-learning platform. By joining our institute you can learn so
                    many things. Nowadays e-learning is the best platform for learning. We offer you many courses, from
                    which you can choose one or more.</p>
                <div class="members">
                    <div class="col-1">
                        <h1>3.2K+</h1>
                        <p>Online Courses</p>
                    </div>
                    <div class="col-2">
                        <h1>600+</h1>
                        <p>Expert Member</p>
                    </div>
                    <div class="col-3">
                        <h1>1K+</h1>
                        <p>Rating & Review</p>
                    </div>
                </div>
                <button class="carousel-btn">Read More</button>
            </div>
        </div>
    </div>
    <div class="container pb-2">
        <div class="join">
            <div class="div-1">
                <h1>Ready To join</h1>
                <p>We have so many courses, that you can enroll. Register for enrollment.</p>
            </div>
            <div class="div-2">

                <button class="join-btn">Register Now</button>
            </div>
        </div>
    </div>
    <div class="container" style="padding-bottom: 30px;">
        <h1 class="review_heading">Some Students Feedback</h1>
        <p class="review_p">Here is some feedback from our formal students</p>
        <div class="my_card">

            <div class="reviews">
                <div class="quote">
                    <i class="fa-solid fa-quote-left"></i>
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
                <p class="feedback">This E-Learning platform is very good. I took a course in web development. They
                    teach us properly. It's interesting that, From a beginner, right now I am a professional web
                    developer. Thanks to EduForge institute.</p>
                <div class="student_profile">
                    <img class="name_img" src="{{ asset('assets') }}/images/demo.jpg" alt="">
                    <div class="name">
                        <h4>Raiyan</h4>
                        <p>Web Developer</p>
                    </div>
                </div>
            </div>

            <div class="reviews">
                <div class="quote">
                    <i class="fa-solid fa-quote-left"></i>
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
                <p class="feedback">This E-Learning platform is very good. I took a course in web development. They
                    teach us properly. It's interesting that, From a beginner, right now I am a professional web
                    developer. Thanks to EduForge institute.</p>
                <div class="student_profile">
                    <img class="name_img" src="{{ asset('assets') }}/images/demo.jpg" alt="">
                    <div class="name">
                        <h4>Raiyan</h4>
                        <p>Web Developer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <h1 class="logo-heading">Trusted by over 800+ companies</h1>
        <div class="client-logo">
            <img src="{{ asset('assets') }}/images/fedex.png" alt="">
            <img src="{{ asset('assets') }}/images/fedex.png" alt="">
            <img src="{{ asset('assets') }}/images/fedex.png" alt="">
            <img src="{{ asset('assets') }}/images/fedex.png" alt="">
            <img src="{{ asset('assets') }}/images/fedex.png" alt="">
            <img src="{{ asset('assets') }}/images/fedex.png" alt="">
        </div>
    </div>

    <div class="footer">
        <h1>EduForge</h1>
        <p>Mirpur DOHS, Dhaka,
            Bangladesh</p>
        <p>Privacy Ploicy | Terms of use</p>
        <div class="social">

        </div>
    </div>
@endsection
