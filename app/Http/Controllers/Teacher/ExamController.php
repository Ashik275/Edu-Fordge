<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Exam;
use App\Models\Materials;
use App\Models\Quiz;
use App\Models\Subjects;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        $subjectIds = explode(',', $teacher->subject_id);
        $classIds = explode(',', $teacher->class_id);
        // Retrieve subjects based on the IDs associated with the teacher
        $subjects = Subjects::whereIn('id', $subjectIds)->latest()->get();
        $classes = Classes::whereIn('id', $classIds)->latest()->get();

        $exams = Exam::where('teacher_id', $teacher->id)->latest()->get();

        return view('teacher.exam.examcreate', [
            'classes' => $classes,
            'subjects' => $subjects,
            'exams' => $exams,

        ]);
    }
  

    public function createExam(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'start_time_input' => 'required',
            'exam_duration' => 'required',
            'subject_id' => 'required',
            'topic' => 'required',
            'class_id' => 'required',
        ]);
        if ($validator->passes()) {

            // Convert the start time to a Carbon instance
            $startTime = Carbon::parse($req->start_time_input);
            $formattedStartTime = $startTime->format('Y-m-d\TH:i:s');

            // Check if an exam with the same class_id already exists for the given exam_date
            $existingExam = Exam::where('class_id', $req->class_id)
                ->whereDate('exam_date', $startTime->toDateString())
                ->first();

            if ($existingExam) {
                return response()->json([
                    'status' => false,
                    'message' => 'An exam for the same class already exists on the selected date.'
                ]);
            } else {
                $exam_duration_in_minutes = $req->exam_duration;
                $teacher = Auth::guard('teacher')->user();
                $exam = new Exam();
                $exam->class_id = $req->class_id;
                $exam->subject_id = $req->subject_id;
                $exam->exam_duration = $exam_duration_in_minutes * 60;
                $exam->exam_name = $req->topic;
                $exam->exam_date = $formattedStartTime;
                $exam->teacher_id = $teacher->id;
                $exam->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Exam created successfully.',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function getMaterials($subject_id)
    {   
        // Fetch materials based on the subject_id
        $materials = Materials::where('subject_id', $subject_id)->get();

        return response()->json($materials);
    }
    public function addQuestion($id)
    {
        return view('teacher.exam.addQuestionModal')->with('exam_id', $id)->render();
    }
    public function viewQuestion($id)
    {  
        $quizes = Quiz::where('exam_id', $id)->latest()->get();
        return view('teacher.exam.viewQuestionModal', compact('quizes'))->render();
        // return view('teacher.exam.addQuestionModal')->with('exam_id', $id)->render();
    }
    // public function createQuiz(Request $data)
    // {   
        
    //     // Validate the incoming data
    //     $validator = Validator::make($data->all(), [
    //         'num_of_question' => 'required|integer', // Ensure num_of_question is provided and is an integer greater than or equal to 1
    //         'exan_id' => 'required|integer', // Ensure exan_id is provided and is an integer
    //     ]);

    //     // If validation fails, return the error response
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors()
    //         ]);
    //     } else {
    //         // Extract exam ID from the array
    //         $examId = $data['exan_id'];
    //         // Iterate through each question
    //         for ($i = 1; $i <= $data['num_of_question']; $i++) {
    //             // Construct the data for the current question
    //             $questionData = [
    //                 'question' => $data['question_' . $i],
    //                 'option_a' => $data['option_a_' . $i],
    //                 'option_b' => $data['option_b_' . $i],
    //                 'option_c' => $data['option_c_' . $i],
    //                 'option_d' => $data['option_d_' . $i],
    //                 'correct_answer' => $data['correct_answer_' . $i],
    //                 'exam_id' => $examId,
    //             ];
    //             // Create a new Quiz instance and save it to the database
    //             $quiz = Quiz::create($questionData);
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Quiz created successfully.',
    //             ]);
    //         }
    //     }
    // }

    public function createQuiz(Request $data)
{   
    // Validate the incoming data
    $validator = Validator::make($data->all(), [
        'num_of_question' => 'required|integer', // Ensure num_of_question is provided and is an integer greater than or equal to 1
        'exan_id' => 'required|integer', // Ensure exan_id is provided and is an integer
    ]);

    // If validation fails, return the error response
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    } else {
        // Extract exam ID from the array
        $examId = $data['exan_id'];
        
        // Iterate through each question
        for ($i = 1; $i <= $data['num_of_question']; $i++) {
            // Construct the data for the current question
            $questionData = [
                'question' => $data['question_' . $i],
                'option_a' => $data['option_a_' . $i],
                'option_b' => $data['option_b_' . $i],
                'option_c' => $data['option_c_' . $i],
                'option_d' => $data['option_d_' . $i],
                'correct_answer' => $data['correct_answer_' . $i],
                'exam_id' => $examId,
            ];
            // Create a new Quiz instance and save it to the database
            Quiz::create($questionData);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Quiz created successfully.',
        ]);
    }
}

}
