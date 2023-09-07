<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //registration form
    public function register_form(){
        return view('users.register');
    }

    public function register_user(Request $request){
        $formFields = $request->validate([
        'name' => ['required', 'min:3'],
        'email' => ['required', 'email', Rule::unique('users', 'email')],
        'password' => 'required|confirmed|min:8'
    ]);
 
    $formFields['password'] = bcrypt($formFields['password']);
        $user = User::create($formFields);
        auth()->login($user); 
        return redirect('/')->with('message', 'User created successfully!');
    }

    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('message', 'You have been logged out!');
    }

    //login form
    public function login(){
        return view('users.login');
    }

    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
            ]);
            If (auth()->attempt($formFields)){
                $request->session()->regenerate();
                return redirect('/')->with('message', 'you have been logged in!');
            }
            return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');

       
    }

}
