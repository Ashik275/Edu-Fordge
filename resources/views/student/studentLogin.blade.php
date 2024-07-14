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
                        <form action="" id="login">
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
                        <button type="button" class="btn" onclick="openRegister()">I'm New Here</button>
                        <a href="#">Forgot Password</a>
                    </div>
                    <div class="card-back">
                        <h2>register</h2>
                        <form action="" id="register">
                            @csrf
                            <input type="text" class="input-box" id="name" name="name" placeholder="Your Name"
                                required>
                            {{-- <input type="tel" class="input-box" placeholder="Phone Number" required> --}}
                            <input type="email" class="input-box" id="email" name="email"
                                placeholder="Your Email ID" required>
                            <select name="class_id" id="class_id" class="input-field input-box" style="background:black">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            <input type="password" class="input-box" name="password" placeholder="Password" required>
                            <input type="password" class="input-box" name="confirm_password" placeholder="Confirm password"
                                required>
                            <button type="submit" class="submit-btn">SUBMIT</button>
                            <input type="checkbox"><span>Remember password</span>
                        </form>
                        <button type="button" class="btn" onclick="openLogin()">I've an account</button>

                    </div>
                </div>
            </div>
        </div>
        <div class="popup-container" id="popupContainer">
            <!-- Popup content -->
            <div class="popup-content">
                <span class="close-btn" onclick="closePopup()">&times;</span>
                {{-- <h2>Error!</h2> --}}
                <p id="pass_msg"></p>
            </div>
        </div>
    </div>
@endsection
