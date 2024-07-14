
{{-- <div class="container">
    <div class="login-box">
        <h2>Teacher Login</h2>
        @if (session('error'))
        <div class="alert alert-danger" role="alert" style="color: #fff">
            {{ session('error') }}
        </div>
        @endif
        <form action="{{route('teacher.authenticate')}}" method="POST">
            @csrf
            <div class="textbox">
                <input type="text" placeholder="Email" name="email" required>
            </div>
            <div class="textbox">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <button class="btn" type="submit">Login</button>
        </form>
    </div>
</div> --}}
@extends('layouts')
@section('content')
    <div class="body">
        <div class="containers">
            <div class="card">
                <div class="inner-box" id="card">
                    <div class="card-front">
                        <div class="logo">
                            <img src="{{ asset('assets') }}/images/navlogo.png" alt="">
                        </div>
                        <h2>LOGIN</h2>
                        <form action="{{ route('teacher.authenticate') }}" method="POST">
                            @csrf
                            <input type="email" name="email" class="input-box" placeholder="Your Email ID"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            <input type="password" name="password" class="input-box" placeholder="Password" required>
                            @error('password')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            <button type="submit" class="submit-btn">SUBMIT</button>
                            <input type="checkbox"><span>Remember password</span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
