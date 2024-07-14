<div class="navbar">
    @if (Route::has('student-login'))
    @auth
    <a href="{{ url('/home') }}"
        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
    @else
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('student-login') }}">Student Login</a>
    <a href="{{ route('teacher-login') }}">Teacher Login</a>
    @endauth
    @endif

</div>