<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assign;
use App\Models\Classes;
use App\Models\Subjects;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssignClassController extends Controller
{
    public function index()
    {
        $classes = Classes::latest()->get();
        $subjects = Subjects::latest()->get();
        $teachers = Teacher::latest()->get();
        $assigns = Assign::latest()->get();
        return view('admin.assignclass.assignclass', [
            'classes' => $classes,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'assigns' => $assigns
        ]);
    }

    public function store(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'day' => 'required',
            'time' => 'required',
        ]);
        if ($validator->passes()) {

            $days = implode(',', $req->day);
            $daysString = "'$days'";
            $teacherExists = DB::table('teachers')->where('id', $req->teacher_id)->exists();
            if ($teacherExists) {
                $conflictingAssignment = DB::select(
                    "
                SELECT * FROM assigns 
                WHERE teacher_id = ? 
                AND time = ? 
                AND (day IN ($daysString) OR day NOT IN ($daysString))",
                    [$req->teacher_id, date('H:i:s', strtotime($req->time))]
                );

                if (!empty($conflictingAssignment)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'The teacher already has an assignment at the specified time on one of the selected day(s).'
                    ]);
                }
            }
            $conflictingClass = DB::select(
                "
            SELECT * FROM assigns 
            WHERE class_id = ? 
            AND time = ? 
            AND (day IN ($daysString))",
                [$req->class_id, date('H:i:s', strtotime($req->time))]
            );

            if (!empty($conflictingClass)) {
                return response()->json([
                    'status' => false,
                    'message' => 'The class already has an assignment at the specified time on one of the selected day(s).'
                ]);
            }
            $assign = new Assign();
            $assign->class_id = $req->class_id;
            $assign->sub_id = $req->subject_id;
            $assign->teacher_id = $req->teacher_id;
            $assign->day = $days;
            $timeFormatted = date('H:i:s', strtotime($req->time));
            $assign->time = $timeFormatted;
            $assign->save();

            return response()->json([
                'status' => true,
                'message' => 'Class assign successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($id, Request $req)
    {

        if (!empty($id)) {
            $assign = Assign::find($id);

            $assign->delete();

            // session()->flash('delete_msg', 'CLient Delete!!');
            // return redirect()->back();
            return response()->json([
                'status' => true,
                'message' => "Assign Class Deleted!!"
            ]);
        }
    }

    public function getsubclass($id)
    {
        $teacher = Teacher::findOrFail($id);

        // Extract subject_id and class_id from the teacher object
        $subjectIds = explode(',', $teacher->subject_id);
        $classId = explode(',', $teacher->class_id);

        $subjects = Subjects::whereIn('id', $subjectIds)->select('id', 'sub_name')->get();

        // Fetch class
        $classes = Classes::whereIn('id', $classId)->select('id', 'class_name')->get();

        // Return subjects and class
        return response()->json(['subjects' => $subjects, 'classes' => $classes]);
    }
}
