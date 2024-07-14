<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::latest()->get();
        return view('admin.class.classList', [
            'classes' => $classes
        ]);
    }
    public function create()
    {
        return view('admin.class.classCreate');
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'class_name' => 'required|unique:classes',
        ]);
        if ($validator->passes()) {
            $category = new Classes();
            $category->class_name = $req->class_name;
            $category->save();

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
        $class = Classes::find($id);
        return view('admin.class.editCLassModal', compact('class'))->render();
    }
    public function update(Request $req, $id)
    {

        $validator = Validator::make($req->all(), [
            'class_name' => 'required|unique:classes',
        ]);
        if ($validator->passes()) {
            $category = Classes::find($id);
            $category->class_name = $req->class_name;
            $category->save();

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
    public function destroy($id, Request $req)
    {

        if (!empty($id)) {
            $class = Classes::find($id);
            $deleted = $class->delete();

            if ($deleted) {
                return response()->json([
                    'status' => true,
                    'message' => "Class deleted successfully"
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Failed to delete the class it is in use"
                ], 500); // Assuming 500 status code for server error
            }
        }
    }
}
