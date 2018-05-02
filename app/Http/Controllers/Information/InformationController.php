<?php

namespace App\Http\Controllers\Information;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InformationController extends Controller
{
    public function index()
    {
        $user = User::all();
        return view('user.personal-information', compact('user'));
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('user.personal-information', compact('user'));
    }

    public function update($id, Request $request){
        $this->validate($request,
            [
                'title' => 'required',
                'content' => 'required',
            ],
            [
                'title.required' => "Vui lòng nhập tiêu đề",
                'content.required' => 'Vui lòng nhập nội dung',
            ]
        );


        $user = User::find($id);
        if (!empty($user)) {
            $user->name = $request->name;
            $user->save();            
        }       
    
        return redirect()->back()->with('success','Lưu thành công!');
    }
}
