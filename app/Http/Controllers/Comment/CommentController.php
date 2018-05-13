<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Mail\ReplyComment;
use App\Model\Comment;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $comment = DB::table('comments')
            ->leftJoin('students', 'students.id', '=', 'comments.created_by')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('classes', 'classes.id', '=', 'students.class_id')
            ->select('comments.*','users.name as userName','classes.name as className');
        if ($user->Role->id >= 5) // admin va phong ctsv thì lấy tất cả user
        {
            //  lấy hết. k cần điều kiện gì
        }else{
            $facultyId = Auth::user()->Faculty->id;
            $comment->where('users.faculty_id', $facultyId);
            if ($user->Role->id >= 4) // chu nhiem khoa thì lấy user thuộc khoa giống
            {
                // lấy ra tất cả user cùng khoa vs user đang đăng nhập
//                $users = array_flatten(User::where('faculty_id', $user->faculty_id)->where('role_id', '<=', 2)->select('id')->get()->toArray());
            }elseif($user->Role->id >=3){ // cố vấn học tập
                $comment->where('classes.staff_id', $user->Staff->id);
            }else { // sinh viên. bán bộ
                $comment->where('comments.created_by', $user->Student->id);
            }
        }
        $comments = $comment->orderBy('comments.id')->get();
        return view('comment.index',compact('comments','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // hàm này dùng để hiển thị ra cái form để nhập liệu khi tạo.
    // ít dùng. vì ta dùng jquery để hiển thị ra rồi.
    public function create()
    {
        return view('comment.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // hàm này để lưuu thông tin khi tạo mới. 
    public function store(Request $request)
    {

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

        $comment = new Comment;
        $comment->created_by = Auth::user()->Student->id;
        $comment->title = $request->title;
        $comment->content = $request->content;
        $comment->save();
    
        return redirect()->route('comment-list')->with('success','Gửi ý kiến thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // hàm này để hiển thị thông tin của 1 comment đã có sẵn. như kiểu bấm vào xem lại thông tin của
    // 1 cái đã có sẵn.
    public function show($id)
    {
        $comment = Comment::find($id);
        return response()->json([
            'comment' => $comment,
            'status' => true
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // hàm này dùng để lưu lại thông tin khi chỉnh sửa. dùm hàm show để show ra rồi thì sửa gì sẽ dùng cá
    // này để lưu lại
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
//            'email_content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $comment = Comment::find($id);
            Mail::to($comment->Student->User->email)->send(new ReplyComment($comment, $request->email_content));
            return response()->json([
                'status' => true
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //xóa. 
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!empty($comment)) {
            $comment->delete();
            return response()->json([
                'comment' => $comment,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }
    // mỗi chức năng đều chia ra controllẻ riêng nên chắc đủ hàm r. k cần tạo thêm
}
