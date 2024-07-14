<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminHomeController extends Controller
{
    public function index()
    {   
        $teacher_count  = Teacher::latest()->get()->count();
        $student_count  = Student::latest()->get()->count();
        return view('admin.admindashboard',[
            'teacher_count' => $teacher_count,
            'student_count' => $student_count,
        ]);
    }
    public function logOut()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
