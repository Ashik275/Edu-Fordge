<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeacherLoginController extends Controller
{
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
      
        if ($validator->passes()) {
         
            if (Auth::guard('teacher')->attempt(['email' => $request->email, 'password' => $request->password])){
                $user = Auth::guard('teacher')->user();
                return redirect()->route('teacher.dashboard');
            } else {
                return redirect()->route('teacher-login')->with('error', 'Either Email/Password is incorrect');
            }
        } else {
            return redirect()->route('teacher.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
}
