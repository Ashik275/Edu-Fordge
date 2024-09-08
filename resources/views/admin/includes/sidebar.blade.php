<nav class="sidebar sidebar-offcanvas" id="sidebar">
    @if (auth()->guard('admin')->check())
        <ul class="nav">
            <li class="nav-item nav-profile">
                <a href="#" class="nav-link">
                    <div class="nav-profile-image">
                        <img src="{{ asset('admin-assets') }}/assets/images/faces/face1.jpg" alt="profile">
                        <span class="login-status online"></span>
                        <!--change to offline or busy as needed-->
                    </div>
                    <div class="nav-profile-text d-flex flex-column">
                        <span class="font-weight-bold mb-2">{{ auth()->user()->name }}</span>
                        {{-- <span class="text-secondary text-small">Project Manager</span> --}}
                    </div>
                    <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#class-dropdown" aria-expanded="false"
                    aria-controls="class-dropdown">
                    <span class="menu-title">Class</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-apps menu-icon"></i>
                </a>
                <div class="collapse" id="class-dropdown">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('classes.classcreate') }}">Add
                                Class</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('classes.classindex') }}">Class
                                List</a></li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#subject-dropdown" aria-expanded="false"
                    aria-controls="subject-dropdown">
                    <span class="menu-title">Subject</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-apps menu-icon"></i>
                </a>
                <div class="collapse" id="subject-dropdown">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('subjects.sub-create') }}">Add
                                Subject</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('subjects.sub-index') }}">Subject
                                List</a></li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="{{ route('teachers.teacher-create') }}">
                    <span class="menu-title">Teachers</span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.student-search') }}">
                    <span class="menu-title">Student Detail</span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('assignclass.teacher-assignclass') }}">
                    <span class="menu-title">Assign Class</span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </a>
            </li>
        </ul>
    @elseif(auth()->guard('teacher')->check())
        <ul class="nav">
            <li class="nav-item nav-profile">
                <a href="#" class="nav-link">
                    <div class="nav-profile-image">
                        <img src="{{ asset('admin-assets') }}/assets/images/faces/face1.jpg" alt="profile">
                        <span class="login-status online"></span>
                        <!--change to offline or busy as needed-->
                    </div>
                    <div class="nav-profile-text d-flex flex-column">
                        <span class="font-weight-bold mb-2">{{ auth()->guard('teacher')->user()->name }}</span>
                    </div>
                    <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.dashboard') }}">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.assignclass') }}">
                    <span class="menu-title">Assign Class & Subject</span>
                    <i class="mdi mdi-book-multiple menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.materials') }}">
                    <span class="menu-title">Materials</span>
                    <i class="mdi mdi-book-multiple menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.meeting-create') }}">
                    <span class="menu-title">Live Class</span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('exam.exam-create') }}">
                    <span class="menu-title">Exam</span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('quiz.generate-quiz') }}">
                    <span class="menu-title">Generate Quiz</span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('quiz.exam-result') }}">
                    <span class="menu-title">Exam Result</span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </a>
            </li>
        </ul>
    @elseif(auth()->guard('student')->check())
        <ul class="nav">
            <li class="nav-item nav-profile">
                <a href="#" class="nav-link">
                    <div class="nav-profile-image">
                        <img src="{{ asset('admin-assets') }}/assets/images/faces/face1.jpg" alt="profile">
                        <span class="login-status online"></span>
                        <!--change to offline or busy as needed-->
                    </div>
                    <div class="nav-profile-text d-flex flex-column">
                        <span class="font-weight-bold mb-2">{{ auth()->guard('student')->user()->name }}</span>
                    </div>
                    <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.dashboard') }}">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.classschedle') }}">
                    <span class="menu-title">Class Schedule</span>
                    <i class="mdi mdi-book-multiple menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.classlecture') }}">
                    <span class="menu-title">Class Lectures</span>
                    <i class="mdi mdi-book-multiple menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.joinclass') }}">
                    <span class="menu-title">Join Class</span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.currentexam') }}">
                    <span class="menu-title">Give Exam</span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </a>
            </li>
        </ul>
    @endif

</nav>
