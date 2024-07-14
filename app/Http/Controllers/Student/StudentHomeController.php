<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assign;
use App\Models\Exam;
use App\Models\Materials;
use App\Models\Meeting;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Subjects;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentHomeController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        $get_exams = Exam::where('class_id', $student->class_id)
            ->whereDate('exam_date', Carbon::today())
            ->latest()
            ->get();

        if ($get_exams->isEmpty()) {
            // No exams scheduled for today
            return view('student.dashboard', ['message' => 'There are no exams scheduled for today.']);
        }
        // Check if there are exams for today
        $today = Carbon::today()->toDateString(); // Get today's date

        foreach ($get_exams as $exam) {
            $exam_date = Carbon::parse($exam->exam_date)->toDateString();

            if ($exam_date === $today) {
                // If exam_date is today, retrieve subject information
                $subject = Subjects::find($exam->subject_id); // Assuming Subject is your model for subjects table

                // Prepare data to pass to view
                $data = [
                    'exam_name' => $exam->exam_name,
                    'subject_name' => $subject->sub_name, // Replace with actual column name from subjects table
                    'exam_time' => Carbon::parse($exam->exam_date)->format('H:i'), // Format time as needed
                ];

                return view('student.dashboard', $data);
            }
        }

        return view('student.dashboard');
        // return 'ji';
    }
    public function currentexam()
    {
        $student = Auth::guard('student')->user();
        $get_exams = Exam::where('class_id', $student->class_id)->get();
        // $quizes = Quiz::where('exam_id', $get_exams->id)->latest()->get();
        // Initialize an array to store quizzes
        $quizzes = [];

        // Iterate over each exam to fetch quizzes related to it
        foreach ($get_exams as $exam) {
            $quiz = Quiz::where('exam_id', $exam->id)->latest()->get();
            // Append quizzes to the result array
            foreach ($quiz as $q) {
                $quizzes[] = $q;
            }
        }
        // Check if results exist for the current student and exam
        $results = Result::where('student_id', $student->id)
            ->whereIn('exam_id', $get_exams->pluck('id'))
            ->pluck('selected_answer', 'question_id')
            ->toArray();
        return view('student.currentexam.currentexam', [
            'quizzes' => $quizzes,
            'student_id' => $student->id,
            'exam_id' => $exam->id,
            'results' => $results,
        ]);
    }
    public function classschedle()
    {
        $student = Auth::guard('student')->user();
        $assigns = Assign::where('class_id', $student->class_id)->get();
        return view('student.classschedle.classschedle', [
            'assigns' => $assigns
        ]);
    }
    public function classlecture()
    {
        $student = Auth::guard('student')->user();
        $materials = Materials::where('class_id', $student->class_id)->get();
        return view('student.classlecture.classlecture', [
            'materials' => $materials
        ]);
    }

    public function joinclass()
    {
        $student = Auth::guard('student')->user();
        $meetings = Meeting::where('class_id', $student->class_id)
            ->where('is_started', 1)
            ->get();
        return view('student.joinclass.joinclass', [
            'meetings' => $meetings
        ]);
    }

    public function giveQuiz(Request $request)
    {
        $resultsData = $request->input('results');
        $totalScore = 0;
        foreach ($resultsData as $resultData) {
            // Create a new Result instance
            $result = new Result();
            $result->exam_id = $resultData['exam_id'];
            $result->student_id = $resultData['student_id'];
            $result->submission_datetime = Carbon::now(); // Current date time
            // Calculate score based on 'isCorrect'
            if ($resultData['isCorrect'] === 'true') {
                $result->score = 1; // Assign 1 mark for correct answer
                $totalScore += 1; // Increment total score
            } else {
                $result->score = 0; // Assign 0 marks for incorrect answer
            }
            $result->selected_answer = $resultData['selectedAnswer'];
            $result->question_id = $resultData['questionId'];

            // Save the result
            $result->save();
        }
        return response()->json([
            'status' => true,
            'totalScore' => $totalScore,
        ]);
    }
}
