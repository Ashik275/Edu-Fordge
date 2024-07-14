<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{

    public function index()
    {   
        $subjects = Subjects::latest()->get();
        return view('admin.subject.subjectList', [
            'subjects' => $subjects
        ]);
    }
    public function create()
    {
        return view('admin.subject.subjectCreate');
    }

    public function store(Request $req)
    {   
        $validator = Validator::make($req->all(), [
            'sub_name' => 'required|unique:subjects',
        ]);
        if ($validator->passes()) {
            $subject = new Subjects();
            $subject->sub_name = $req->sub_name;
            $subject->save();

            return response()->json([
                'status' => true,
                'message' => 'Classes added successfully'
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
        $subject = Subjects::find($id);
        return view('admin.subject.editSubjectModal', compact('subject'))->render();
    }

    public function update(Request $req,$id)
    {
        
        $validator = Validator::make($req->all(), [
            'sub_name' => 'required|unique:subjects',
        ]);
        if ($validator->passes()) {
            $subject = Subjects::find($id);
            $subject->sub_name = $req->sub_name;
            $subject->save();

            return response()->json([
                'status' => true,
                'message' => 'Subject added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id,Request $req)
    {
     
        if (!empty($id)) {
            $subject = Subjects::find($id);

            $subject->delete();

            // session()->flash('delete_msg', 'CLient Delete!!');
            // return redirect()->back();
            return response()->json([
                'status' => true,
                'message' => " Deleted!!"
            ]);
        }
    }
}
