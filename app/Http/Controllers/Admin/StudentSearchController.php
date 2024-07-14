<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentSearchController extends Controller
{
    //
    public function search(){
        $classes = classes::latest()->get();
        return view('admin.student-search.student-search',[
          'classes' =>$classes  
        ]);
    }

    public function searchdata($id)
    {
        $students = Student::where('class_id', $id)->latest()->get();
        return view('admin.student-search.searchdata', compact('students'))->render();
    }
}
