<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    //

    public function studentlogin()
    {
        $classes = Classes::latest()->get();
        return view('student.studentLogin', [
            'classes' => $classes
        ]);
    }
    public function store(Request $req)
    {   

        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'email' => 'required|unique:students,email',
            'password' => 'required',
        ]);
        if ($validator->passes()) {
            if ($req->password == $req->confirm_password) {
                $user = new Student();
                $user->name = $req->name;
                $user->email = $req->email;
                $user->class_id = $req->class_id;
                $space_position = strpos($user->name, ' ');
                if ($space_position !== false) {
                    $name_part = substr($user->name, 0, $space_position);
                } else {
                    $name_part = $user->name;
                }

                // Convert the extracted part to lowercase
                $name_part_lower = strtolower($name_part);

                // Generate the registration number
                $reg_no = $name_part_lower . '-' . time();
                $user->reg_no = $reg_no;
                $user->password =  Hash::make($req->password);
                $user->save();
                return response()->json([
                    'status' => true,
                    'msg' => "User Created Successfuly"
                ]);
            } else {
                return response()->json([
                    'status' => "password",
                    'msg' => "Password Didnt Match"
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
      
        if ($validator->passes()) {
         
            if (Auth::guard('student')->attempt(['email' => $request->email, 'password' => $request->password])){
                $user = Auth::guard('student')->user();
                // return redirect()->route('student-dashboard');
                return response()->json([
                    'status' => true,
                    'msg' => "good"
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => "Either Email/Password is incorrect"
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function logOut()
    {
        Auth::guard('student')->logout();
        return redirect()->route('student-login');
    }
}
