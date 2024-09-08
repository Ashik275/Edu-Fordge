<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Exam;
use App\Models\Materials;
use App\Models\Quiz;
use App\Models\Score;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Smalot\PdfParser\Parser;
use GuzzleHttp\Client as GuzzleClient;

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
        $materials =  Materials::whereIn('class_id', $classIds)->get();
        // return $materials;
        $exams = Exam::where('teacher_id', $teacher->id)->latest()->get();

        return view('teacher.exam.generatecreate', [
            'classes' => $classes,
            'subjects' => $subjects,
            'exams' => $exams,
            'materials' => $materials

        ]);
    }
    public function searchResult($examid)
    {
        // Fetch results using Eloquent
        $results = Score::where('exam_id', $examid)->get();

        // Return or process the results as needed
        return view('teacher.exam.result', compact('results'))->render();
    }
    public function examResult()
    {
        $teacher = Auth::guard('teacher')->user();
        $subjectIds = explode(',', $teacher->subject_id);
        $classIds = explode(',', $teacher->class_id);
        // Retrieve subjects based on the IDs associated with the teacher
        $subjects = Subjects::whereIn('id', $subjectIds)->latest()->get();
        $classes = Classes::whereIn('id', $classIds)->latest()->get();
        // return $materials;
        $exams = Exam::where('teacher_id', $teacher->id)->latest()->get();
        return view('teacher.exam.examresult', [
            'classes' => $classes,
            'subjects' => $subjects,
            'exams' => $exams,

        ]);
    }
    public function uploadQuiz(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'subject_id' => 'required',
            'exam_id' => 'required',
            'difficulty' => 'required',
            'questionlanguage' => 'required',
            'no_ques' => 'required'
        ]);
        if ($validator->passes()) {
            $fileName = $request->subject_id; // The file name you obtained from the request
            if($fileName != ''){
            $filePath = public_path('materials/' . $fileName);
            // Parse the PDF
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();
            }else{
                $text = $request->content;
            }
            $quizLevel = $request->difficulty;
            $language = $request->questionlanguage; // Get the selected language
            $no_ques = $request->no_ques; // Get the selected language
            $exam_id = $request->exam_id; // Get the selected language

            $prompt = $this->generatePrompt($text, $quizLevel, $language, $no_ques);
            // Initialize the Guzzle client
            $client = new GuzzleClient();

            try {
                // Make the request to the OpenAI API
                $response = $client->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('OPEN_AI_API'),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.3,
                        'max_tokens' => 1000,
                        'top_p' => 1,
                        'frequency_penalty' => 0,
                        'presence_penalty' => 0,
                    ],
                ]);

                // Decode the response
                $responseBody = $response->getBody()->getContents();
                $responseData = json_decode($responseBody, true);

                // Extract MCQs from the response
                $questionsContent = $responseData['choices'][0]['message']['content'];
                $questionsData = json_decode($questionsContent, true);

                // Check if MCQs are present
                if (isset($questionsData['mcqs'])) {
                    $questions = $questionsData['mcqs'];
                //    dd($questions);
                    // Insert each question into the database
                    foreach ($questions as $question) {
                        Quiz::create([
                            'question' => $question['mcq'],
                            'option_a' => $question['options']['a'],
                            'option_b' => $question['options']['b'],
                            'option_c' => $question['options']['c'],
                            'option_d' => $question['options']['d'],
                            'correct_answer' => $question['correct_answer'],
                            'exam_id' => $exam_id, // Example exam_id, you might want to handle this dynamically
                        ]);
                    }
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Quiz created successfully.',
                ]);
            } catch (\Exception $e) {
                // Handle exceptions
                dd($e->getMessage());
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    // private function generatePrompt($text, $quizLevel, $language, $noQuiz)
    // {
    //     $languageInstructions = [
    //         'English' => 'You are an expert in generating multiple choice questions (MCQs) in English.',
    //         'Bengali' => 'আপনি বাংলা ভাষায় MCQ (বহুনির্বাচনী প্রশ্ন) তৈরি করতে একজন বিশেষজ্ঞ।',
    //     ];

    //     $instructions = sprintf(
    //         'Text: %s\n%s\nBased on the above text, please create a quiz consisting of %d multiple choice questions with a difficulty level of %s.\nEach question should have four options: a, b, c, and d.\nEnsure that the questions and their options are written in %s.\n\nPlease format your response exactly like the RESPONSE_JSON example below. Each question should be clearly numbered and include a question text, four options, and the correct answer. Make sure the options are varied and not obviously misleading.\n\nHere is the RESPONSE_JSON format:\n\n%s',
    //         $text,
    //         $languageInstructions[$language],
    //         $noQuiz,
    //         $quizLevel,
    //         $language,
    //         json_encode([
    //             "mcqs" => array_map(function ($i) {
    //                 return [
    //                     "mcq" => "Example question $i based on the text.",
    //                     "options" => [
    //                         "a" => "Option a for question $i",
    //                         "b" => "Option b for question $i",
    //                         "c" => "Option c for question $i",
    //                         "d" => "Option d for question $i",
    //                     ],
    //                     "correct_answer" => "a"  // Specify the correct option here
    //                 ];
    //             }, range(1, 3)) // This is a placeholder, will be replaced by actual questions
    //         ], JSON_PRETTY_PRINT)
    //     );

    //     return $instructions;
    // }
    private function generatePrompt($text, $quizLevel, $language, $noQuiz)
{
    $languageInstructions = [
        'English' => 'You are an expert in generating multiple choice questions (MCQs) in English.',
        'Bengali' => 'আপনি বাংলা ভাষায় MCQ (বহুনির্বাচনী প্রশ্ন) তৈরি করতে একজন বিশেষজ্ঞ।',
    ];

    // Helper function to generate a random answer key
    function getRandomAnswerKey() {
        $keys = ['a', 'b', 'c', 'd'];
        shuffle($keys); // Randomly shuffle the keys
        return $keys[0]; // Return the first key as the correct answer
    }

    $instructions = sprintf(
        'Text: %s\n%s\nBased on the above text, please create a quiz consisting of %d multiple choice questions with a difficulty level of %s.\nEach question should have four options: a, b, c, and d.\nEnsure that the questions and their options are written in %s.\n\nPlease format your response exactly like the RESPONSE_JSON example below. Each question should be clearly numbered and include a question text, four options, and the correct answer. Make sure the options are varied and not obviously misleading.\n\nHere is the RESPONSE_JSON format:\n\n%s',
        $text,
        $languageInstructions[$language],
        $noQuiz,
        $quizLevel,
        $language,
        json_encode([
            "mcqs" => array_map(function ($i) {
                // Generate a random correct answer key
                $correctAnswer = getRandomAnswerKey();

                // Define the options
                $options = [
                    "a" => "Option a for question $i",
                    "b" => "Option b for question $i",
                    "c" => "Option c for question $i",
                    "d" => "Option d for question $i",
                ];

                return [
                    "mcq" => "Example question $i based on the text.",
                    "options" => $options,
                    "correct_answer" => $correctAnswer // Set the random correct answer here
                ];
            }, range(1, $noQuiz)) // Use $noQuiz to determine the number of questions
        ], JSON_PRETTY_PRINT)
    );

    return $instructions;
}

}
