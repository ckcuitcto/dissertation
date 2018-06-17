<?php

namespace App\Http\Controllers\Information;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;

class InformationController extends Controller
{
    public function index()
    {
        $user = $this->getUserLogin();
        $students = $this->getStudentByRoleUserLogin($user);

        return view('user.personal-information-list', compact('students'));
    }

    public function show($id)
    {
        $user = User::where('users_id',$id)->first();
        if(!empty($user)) {
            $userLogin = $this->getUserLogin();
            if($user->users_id != $userLogin->users_id){
                return view('errors.403');
            }
//            $this->authorize($user,'view');

            if (!empty($user->birthday)) {
                $user->birthday = Carbon::parse($user->birthday)->format('d/m/Y');
            }
            return view('user.personal-information-show', compact('user'));
        }
        return redirect()->back();
    }

    public function update($id, Request $request){

        $time = strtotime("-13 year", time());
        $date = date("d/m/Y", $time);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id.',users_id',
            'gender' => 'required',
            'address' => 'required',
            'phone_number' => 'required|numeric|phone',
            'birthday' => 'required|date_format:d/m/Y|before:'.$date
        ],[
            'name.required' => "Vui lòng nhập tên",
            'email.required' => "Vui lòng nhập email",
            'email.unique' => "Email đã tồn tại",
            'gender.required' => "Vui lòng chọn giới tính",
            'address.required' => "Vui lòng nhập địa chỉ",
            'phone_number.required' => "Vui lòng nhập số điện thoại",
            'phone_number.numeric' => "Số điện thoại phải là số",
            'phone_number.phone' => "Số điện thoại không đúng định dạng",
            'birthday.required' => 'Vui lòng nhập ngày sinh',
            'birthday.date_format' => 'Ngày sinh không đúng định dạng. VD:24/08/1996',
            'birthday.before' => 'Ngày sinh không họp lệ',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
             $user = User::where('users_id',$id)->first();
            if (!empty($user)) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->gender = $request->gender;
                $user->address = $request->address;
                $user->phone_number = $request->phone_number;
                $user->birthday = $this->formatDate($request->birthday);
                if($request->avatar){
                    $file = $request->avatar;

                    $fileName = $this->convert_vi_to_en(str_random(8) . "_" . $file->getClientOriginalName());
                    while (file_exists("image/avatar/" . $fileName)) {
                        $fileName = $this->convert_vi_to_en(str_random(8) . "_" . $file->getClientOriginalName());
                    }
                    $file->move('image/avatar/', $fileName);

                    if(file_exists("image/avatar/" . $user->avatar)){ // nếu  sản phẩm đã có ảnh thì sẽ xóa ảnh trong thư mục
//                        unlink("image/avatar/" . $user->avatar);
                        Storage::delete('public/image/avatar/'.$user->avatar);
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
