<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    //
    function  create(Request $request){
        //validate inputs
        $request->validate([
            'name'=>'required',
            'hospital'=>'required',
            'email'=>'required|email|unique:doctors,email',
            'password'=>'required|min:5|max:30',
            'cpassword'=>'required|min:5|max:30|same:password'
        ]);
                //If Validation successful create new user into doctors table
        $user = new Doctor();
        $user->name = $request->name;
        $user->hospital = $request->hospital;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $save = $user->save();

        if($save){
            return redirect()->back()->with('success', 'You are now registered successfully');
        } else {
            return redirect()->back()->with('fail', 'something  went wrong, failed to register');
        }
    }

    function check(Request $request){
        //validate inputs
        $request->validate([
            'email'=>'required|email|exists:doctors,email',
            'password'=>'required|min:5|max:30'
        ],[
            'email.exists'=>'Sorry!! You have not registered yet!!'
        ]);

        $creds = $request->only('email', 'password');
        if(Auth::guard('doctor')->attempt($creds)){
            return redirect()->route('doctor.home');
        }else{
            return redirect()->route('doctor.login')->with('fail', 'Incorrect Credentials');
        }
    }

    function logout(){
        Auth::guard('doctor')->logout();
        return redirect('/');
    }
}
