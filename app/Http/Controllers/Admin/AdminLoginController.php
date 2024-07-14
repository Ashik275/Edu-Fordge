<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    //
    public function index()
    {
        return view('admin.adminlogin');
    }
    public function authenticate(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
      
        if ($validator->passes()) {
         
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){
                $user = Auth::guard('admin')->user();
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('admin.login')->with('error', 'Either Email/Password is incorrect');
            }
        } else {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
}
