<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Exam;
use App\Models\Materials;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class GenerateQuiz extends Controller
{
    public function generateExam()
    {  
       
        $teacher = Auth::guard('teacher')->user();
        $subjectIds = explode(',', $teacher->subject_id);
        $classIds = explode(',', $teacher->class_id);
        // Retrieve subjects based on the IDs associated with the teacher
        $subjects = Subjects::whereIn('id', $subjectIds)->latest()->get();
        $classes = Classes::whereIn('id', $classIds)->latest()->get();
        $materials = Materials::whereIn('id', $classIds)->latest()->get();

        $exams = Exam::where('teacher_id', $teacher->id)->latest()->get();

        return view('teacher.exam.generatecreate', [
            'classes' => $classes,
            'subjects' => $subjects,
            'exams' => $exams,
            'materials' => $materials

        ]);
    }
}
