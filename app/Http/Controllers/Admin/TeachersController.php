<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Subjects;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeachersController extends Controller
{
    public function create()
    {   
        $classes = Classes::latest()->get();
        $subjects = Subjects::latest()->get();
        $teachers = Teacher::latest()->get();
        return view('admin.teacher.teacherCreate', [
            'classes' => $classes,
            'subjects' => $subjects,
            'teachers' => $teachers
        ]);
    }

    public function store(Request $req)
    {


        $validator = Validator::make($req->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
            'password' => 'required',
            'name' => 'required',
            'email' => 'required|unique:teachers',
            'nid' => 'required|unique:teachers',
        ]);
        if ($validator->passes()) {
            $subjectIds = implode(',', $req->subject_id);
            $classIds = implode(',', $req->class_id);
           
            $space_position = strpos($req->name, ' ');
            if ($space_position !== false) {
                $name_part = substr($req->name, 0, $space_position);
            } else {
                $name_part = $req->name;
            }

            // Convert the extracted part to lowercase
            $name_part_lower = strtolower($name_part); 

            $teacher = new Teacher();
            $teacher->class_id = $classIds;
            $teacher->subject_id = $subjectIds;
            $teacher->name = $req->name;
            $teacher->email = $req->email;
            $teacher->nid = $req->nid;
            $teacher->teacher_id = $name_part_lower . '-' . time();
            $teacher->password = $req->password;
            $teacher->save();

            return response()->json([
                'status' => true,
                'message' => 'Teacher added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function update(Request $req, $id)
    {
        $subjectIds = implode(',', $req->subject_id);
        $classIds = implode(',', $req->class_id);


        $validator = Validator::make($req->all(), [
            'class_id' => 'required|array',
            'subject_id' => 'required|array',
            // 'password' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);
        if ($validator->passes()) {
            $teacher = Teacher::find($id);
            $teacher->class_id = $classIds;
            $teacher->subject_id = $subjectIds;
            $teacher->name = $req->name;
            $teacher->email = $req->email;
            // $teacher->password = $req->password;
            $teacher->save();

            return response()->json([
                'status' => true,
                'message' => 'Teacher updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id)
    {
        $classes = Classes::latest()->get();
        $subjects = Subjects::latest()->get();
        $teacher = Teacher::find($id);
        return view('admin.teacher.editTeacherModal', compact('teacher', 'subjects', 'classes'))->render();
    }
    public function destroy($id, Request $req)
    {

        if (!empty($id)) {
            $teacher = Teacher::find($id);

            $teacher->delete();

            // session()->flash('delete_msg', 'CLient Delete!!');
            // return redirect()->back();
            return response()->json([
                'status' => true,
                'message' => "Teacher Deleted!!"
            ]);
        }
    }
}
