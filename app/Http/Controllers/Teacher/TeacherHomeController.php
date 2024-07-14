<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherHomeController extends Controller
{
    //
    public function index()
    {   
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.dashboard', [
            'teacher' => $teacher
        ]);
       
    }
    public function logOut()
    {
        Auth::guard('teacher')->logout();
        return redirect()->route('teacher-login');
    }
    public function assignclass()
    {
        return redirect()->route('teacher.assignclass');
    }
}
