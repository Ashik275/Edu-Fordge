<?php

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AssignClassController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StudentSearchController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeachersController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\StudentHomeController;
use App\Http\Controllers\Teacher\ExamController;
use App\Http\Controllers\Teacher\GenerateQuiz;
use App\Http\Controllers\Teacher\TeacherHomeController;
use App\Http\Controllers\Teacher\TeacherLoginController;
use App\Http\Controllers\Teacher\TeacherRedirectController;
use App\Http\Controllers\Teacher\ZoomMeetingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::group(['prefix' => 'admin'], function () {
    Route::middleware('isGuest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });
    Route::middleware('isAdmin')->group(function () {
        Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [AdminHomeController::class, 'logOut'])->name('admin.logout');

        ## Create Class
        Route::get('/classes/classindex', [ClassController::class, 'index'])->name('classes.classindex');
        Route::get('/classes/classcreate', [ClassController::class, 'create'])->name('classes.classcreate');
        Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
        Route::post('/classes/{id}', [ClassController::class, 'update'])->name('classes.update');
        Route::get('/classes/{id}', [ClassController::class, 'edit'])->name('classes.edit');
        Route::delete('/classes/{delete}', [ClassController::class, 'destroy'])->name('classes.delete');

        ## Subject create
        Route::get('/subjects/subindex', [SubjectController::class, 'index'])->name('subjects.sub-index');
        Route::get('/subjects/subcreate', [SubjectController::class, 'create'])->name('subjects.sub-create');
        Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.sub-store');
        Route::get('/subjects/{id}', [SubjectController::class, 'edit'])->name('subjects.sub-edit');
        Route::post('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.sub-update');
        Route::delete('/subjects/{delete}', [SubjectController::class, 'destroy'])->name('subjects.sub-delete');

        ##Teacher Create 
        Route::get('/teachers/teachercreate', [TeachersController::class, 'create'])->name('teachers.teacher-create');
        Route::post('/teachers', [TeachersController::class, 'store'])->name('teachers.teacher-store');
        Route::delete('/teachers/{id}', [TeachersController::class, 'destroy'])->name('teachers.teacher-delete');
        Route::get('/teachers/{id}', [TeachersController::class, 'edit'])->name('teachers.teacher-edit');
        Route::post('/teachers/{id}', [TeachersController::class, 'update'])->name('teachers.teacher-update');

        #student serach 
        Route::get('/student', [StudentSearchController::class, 'search'])->name('student.student-search');
        Route::get('/student/{id}', [StudentSearchController::class, 'searchdata'])->name('student.student-searchdata');


        ## Assign Class 
        Route::get('/assignclass/teacherassignclass', [AssignClassController::class, 'index'])->name('assignclass.teacher-assignclass');
        Route::post('/assignclass', [AssignClassController::class, 'store'])->name('assignclass.teacher-assignclassstore');
        Route::post('/assignclass/{id}', [AssignClassController::class, 'getsubclass'])->name('assignclass.teacher-getsubclass');
        Route::delete('/assignclass/{delete}', [AssignClassController::class, 'destroy'])->name('assignclass.teacher-assignclassdelete');
    });
});
Route::group(['prefix' => 'student'], function () {
    Route::get('/students/login', [StudentController::class, 'studentlogin'])->name('student-login');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::post('/authenticate', [StudentController::class, 'authenticate'])->name('students.authenticate');


    Route::middleware('student.auth')->group(function () {
        Route::get('/dashboard', [StudentHomeController::class, 'index'])->name('student.dashboard');
        Route::get('/logout', [StudentController::class, 'logOut'])->name('student.logout');
        Route::post('/stduent-quiz/give-quiz', [StudentHomeController::class, 'giveQuiz'])->name('stduent-quiz.give-quiz');
        Route::get('/student/classschedle', [StudentHomeController::class, 'classschedle'])->name('student.classschedle');
        Route::get('/student/classlecture', [StudentHomeController::class, 'classlecture'])->name('student.classlecture');
        Route::get('/student/joinclass', [StudentHomeController::class, 'joinclass'])->name('student.joinclass');
        Route::get('/student/currentexam', [StudentHomeController::class, 'currentexam'])->name('student.currentexam');
    });
});
Route::group(['prefix' => 'teacher'], function () {
    Route::get('/teachers/login', function () {
        return view('teacher.teacherLogin');
    })->name('teacher-login');
    Route::post('/authenticate', [TeacherLoginController::class, 'authenticate'])->name('teacher.authenticate');
    Route::middleware('teacher.auth')->group(function () {
        Route::get('/dashboard', [TeacherHomeController::class, 'index'])->name('teacher.dashboard');
        Route::get('/logout', [TeacherHomeController::class, 'logOut'])->name('teacher.logout');

        ## Techer Routing
        Route::get('/teacher/assignclass', [TeacherRedirectController::class, 'index'])->name('teacher.assignclass');
        Route::get('/teacher/materials', [TeacherRedirectController::class, 'materials'])->name('teacher.materials');
        Route::get('/teacher/profile', [TeacherRedirectController::class, 'profile'])->name('teacher.profile');
        Route::post('/teacher/materials/store', [TeacherRedirectController::class, 'materialsstore'])->name('teacher.teacher-material-store');
        Route::post('/teacher/password/update/{id}', [TeacherRedirectController::class, 'update'])->name('teacher.teacher-pass-edit');
        Route::post('/teacher/image/update', [TeacherRedirectController::class, 'imageupdate'])->name('teacher.teacher-image-edit');
        Route::delete('/teacher/materials/{id}', [TeacherRedirectController::class, 'destroy'])->name('teacher.teacher-material-delete');

        ## Create Exam exam.exam-create
        Route::get('/exam/create-exam', [ExamController::class, 'index'])->name('exam.exam-create');
        Route::post('/exam/add-exam', [ExamController::class, 'createExam'])->name('exam.exam-add');
        Route::post('/quiz/add-quiz', [ExamController::class, 'createQuiz'])->name('quiz.quiz-add');
        Route::get('/exam/{id}', [ExamController::class, 'addQuestion'])->name('exam.add-quiz');
        Route::get('/exam-view/{id}', [ExamController::class, 'viewQuestion'])->name('exam.add-view');
        ## generate Exam 
        Route::get('/quiz/generate-quiz', [GenerateQuiz::class, 'generateExam'])->name('quiz.generate-quiz');

        ## Zoom meeting create
        Route::get('/teacher/meeting/create', [ZoomMeetingController::class, 'create'])->name('teacher.meeting-create');

        Route::post('/teacher/meeting', [ZoomMeetingController::class, 'createMeeting'])->name('teacher.meeting.live.create');
        Route::get('/teacher/meeting/{id}/start', [ZoomMeetingController::class, 'startMeeting'])->name('teacher.meeting.start');

        Route::delete('/teacher/meeting/{id}', [ZoomMeetingController::class, 'deleteMeeting'])->name('teacher.meeting.delete');
    });
});
