<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assign;
use App\Models\Classes;
use App\Models\Materials;
use App\Models\Subjects;
use App\Models\Teacher;
use App\Models\TeacherPic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TeacherRedirectController extends Controller
{
    //
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        $assigns = Assign::where('teacher_id', $teacher->id)->latest()->get();
        return view('teacher.assignclass.assignclass', [
            'teacher' => $teacher,
            'assigns' => $assigns
        ]);
    }
    public function profile()
    {
        $teacher = Auth::guard('teacher')->user();
        $teacherpic = TeacherPic::where('teacher_id', $teacher->id)->latest()->first();
        // dd($teacherpic);
        
        return view('teacher.profile.profile', [
            'teacher' => $teacher,
            'teacherpic' => $teacherpic,
        ]);
    }
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);
        if ($validator->passes()) {
            $teacher = Teacher::find($id);
            // $teacher->class_id = $classIds;
            // $teacher->subject_id = $subjectIds;
            // $teacher->name = $req->name;
            // $teacher->email = $req->email;
            $teacher->password = $request->password;
            $teacher->save();

            return response()->json([
                'status' => true,
                'message' => 'Teacher password updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function materials()
    {
        $teacher = Auth::guard('teacher')->user();
        $subjectIds = explode(',', $teacher->subject_id);
        $classIds = explode(',', $teacher->class_id);



        // Retrieve subjects based on the IDs associated with the teacher
        $subjects = Subjects::whereIn('id', $subjectIds)->latest()->get();
        $classes = Classes::whereIn('id', $classIds)->latest()->get();
        $materials = Materials::where('teacher_id', $teacher->id)->get();
        return view('teacher.materials.materials', [
            'classes' => $classes,
            'subjects' => $subjects,
            'materials' => $materials
        ]);
    }
    public function materialsstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required',
            'file' => 'required|file|mimes:pdf,jpg,jpeg|max:20480',
        ]);
        if ($validator->passes()) {
            $teacher = Auth::guard('teacher')->user();
            // Handle file upload
            $file = $request->file('file');
            $fileName = $request->title . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('materials'), $fileName);

            // Insert data into Materials table
            $material = new Materials();
            $material->class_id = $request->class_id;
            $material->subject_id = $request->subject_id;
            $material->title = $request->title;
            $material->teacher_id = $teacher->id;
            $material->file = $fileName; // Save the file name in the database
            $material->save();

            return response()->json([
                'status' => true,
                'message' => 'Material created successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function imageupdate(Request $req)
    {
        if ($req->hasFile('file') && $req->has('teacher_id')) {
            $teacher_id = $req->teacher_id;
            $file = $req->file('file');

            // Generate a unique file name
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            // Move the uploaded file to the public/teacher directory
            $file->move(public_path('teacher'), $fileName);

            // Find the TeacherPic record by teacher_id
            $profile_pic = TeacherPic::where('teacher_id', $teacher_id)->first();
          
            if ($profile_pic) {
                // If the record exists, delete the previous picture
                $previousImagePath = public_path('teacher') . '/' . $profile_pic->image_path;
               
                if (File::exists($previousImagePath)) {
                    unlink($previousImagePath);
                }

                // Update the image_path
                $profile_pic->image_path = $fileName;
                $profile_pic->save();
            } else {
                // If the record doesn't exist, create a new record
                $profile_pic = new TeacherPic();
                $profile_pic->teacher_id = $teacher_id;
                $profile_pic->image_path = $fileName;
                $profile_pic->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Profile picture updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'File or teacher_id missing',
            ]);
        }
    }
    public function destroy($id, Request $req)
    {
        if (!empty($id)) {
            // Find the material
            $material = Materials::find($id);

            // Check if the material exists
            if ($material) {
                // Delete the attachment from public folder
                $filePath = public_path('materials/' . $material->file);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }

                // Delete the material record
                $material->delete();

                // Return success response
                return response()->json([
                    'status' => true,
                    'message' => "Material and its attachment deleted successfully!"
                ]);
            } else {
                // Material not found
                return response()->json([
                    'status' => false,
                    'message' => "Material not found."
                ], 404);
            }
        } else {
            // Invalid material ID provided
            return response()->json([
                'status' => false,
                'message' => "Invalid material ID."
            ], 400);
        }
    }
}
