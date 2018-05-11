<?php

namespace App\Http\Controllers\Information;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InformationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $students = $this->getStudentByRoleUserLogin($user);

        return view('user.personal-information-list', compact('students'));
    }

    public function show($id)
    {
        // $user = Auth::user();
        $user = User::find($id);

        $this->authorize($user->Student,'view');

        return view('user.personal-information-show', compact('user'));
    }

    public function update($id, Request $request){

        if($request->has('submit')) {
            $this->validate($request,
            [
                'name' => 'required',
                'email' => 'required',
                'gender' => 'required',
                'address' => 'required',
                'phone_number' => 'required',
                'birthday' => 'required'
            ],
            [
                'name.required' => "Vui lòng nhập tên",
                'email.required' => "Vui lòng nhập email",
                'gender.required' => "Vui lòng nhập giới tính",
                'address.required' => "Vui lòng nhập địa chỉ",
                'phone_number.required' => "Vui lòng nhập số điện thoại",
                'birthday.required' => 'Vui lòng nhập ngày sinh',
            ]
            );
            $user = Auth::user();
            // $user = User::find($id);
            if (!empty($user)) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->gender = $request->gender;
                $user->address = $request->address;
                $user->phone_number = $request->phone_number;
                $user->birthday = $request->birthday;
                $user->save();   
            }    
            return redirect()->back()->with(['flash_message' => 'Sửa thành công']);
        }else{
            return redirect()->back();
        }
    }
}
