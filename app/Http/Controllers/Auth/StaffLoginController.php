<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StaffLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:staff');
    }
    public function showLoginForm()
    {
        return view('auth.login-animated');
    }
    public function login(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
            'loginEmail'   => 'required',
            'loginPassword' => 'required|min:6'
        ]);
        // Attempt to log the user in
            if (Auth::guard('staff')->attempt(['id' => $request->loginEmail, 'password' => $request->loginPassword], $request->remember)) {
            // if successful, then redirect to their intended location
            return redirect()->intended(route('staff.dashboard'));
        }
        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('id', 'remember'));
    }
}
