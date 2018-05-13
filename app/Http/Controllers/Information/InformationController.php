<?php

namespace App\Http\Controllers\Information;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

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
        $user = User::find($id);
        if(!empty($user)) {
            $this->authorize($user->Student,'view');

            if (!empty($user->birthday)) {
                $user->birthday = Carbon::parse($user->birthday)->format('d/m/Y');
            }
            return view('user.personal-information-show', compact('user'));
        }
        return redirect()->back();
    }

    public function update($id, Request $request){

//        var_dump(1);
//        dd($request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'phone_number' => 'required|numeric|phone',
            'birthday' => 'required|date_format:d/m/Y'
        ], [
            'name.required' => "Vui lòng nhập tên",
            'email.required' => "Vui lòng nhập email",
            'gender.required' => "Vui lòng nhập giới tính",
            'address.required' => "Vui lòng nhập địa chỉ",
            'phone_number.required' => "Vui lòng nhập số điện thoại",
            'phone_number.numeric' => "Số điện thoại phải là số",
            'phone_number.phone' => "Số điện thoại không đúng định dạng",
            'birthday.required' => 'Vui lòng nhập ngày sinh',
            'birthday.date_format' => 'Ngày sinh không đúng định dạng. VD:24/08/1996',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
//            $user = Auth::user();
             $user = User::find($id);
            if (!empty($user)) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->gender = $request->gender;
                $user->address = $request->address;
                $user->phone_number = $request->phone_number;
                $user->birthday = $this->formatDate($request->birthday);
                if($request->avatar){
                    $file = $request->avatar;

                    $fileName = str_random(8) . "_" . $file->getClientOriginalName();
                    while (file_exists("image/avatar/" . $fileName)) {
                        $fileName = str_random(8) . "_" . $file->getClientOriginalName();
                    }
                    $file->move('image/avatar/', $fileName);

                    if(file_exists("image/avatar/" . $user->avatar)){ // nếu  sản phẩm đã có ảnh thì sẽ xóa ảnh trong thư mục
                        unlink("image/avatar/" . $user->avatar);
                    }

                    $user->avatar = $fileName;



                }
                $user->save();
            }
            return response()->json([
                'status' => true
            ], 200);
        }
    }

    public function checkFileUpload(Request $request)
    {

        $arrFileType = array('img', 'jpg', 'pdf', 'png', 'jpeg', 'bmp');

        $file = $request->file('fileUpload');

        if($file){
            if(!in_array($file->getClientOriginalExtension(),$arrFileType))
            {
                $arrMessage = array("fileImport" => ["File ".$file->getClientOriginalName()." không hợp lệ "] );
                return response()->json([
                    'status' => false,
                    'arrMessages' => $arrMessage
                ], 200);
            }else{
                return response()->json([
                    'status' => true,
                ], 200);
            }
        }
    }
}
