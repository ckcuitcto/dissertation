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
use Yajra\DataTables\DataTables;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('comment.index');
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
        $user = $this->getUserLogin();
        if ($user->Role->weight >= ROLE_COVANHOCTAP) {
            return view('errors.403');
        }
        return view('comment.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    // hàm này để lưuu thông tin khi tạo mới. 
    public function store(Request $request)
    {
        $user = $this->getUserLogin();
        if ($user->Role->weight >= ROLE_COVANHOCTAP) {
            return view('errors.403');
        }

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
        $comment->created_by = $this->getUserLogin()->Student->id;
        $comment->title = $request->title;
        $comment->content = $request->content;
        $comment->save();

        return redirect()->route('comment-list')->with('success', 'Gửi ý kiến thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    // hàm này để hiển thị thông tin của 1 comment đã có sẵn. như kiểu bấm vào xem lại thông tin của
    // 1 cái đã có sẵn.
    public function show($id)
    {
        $comment = Comment::find($id);
        if (!empty($comment)) {
            return response()->json([
                'comment' => $comment,
                'status' => true
            ], 200);
        } else {
            return response()->json([
                'status' => false
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
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
            $comment->reply = $request->email_content;
            $comment->save();
            //nếu sinh viên có email thì sẽ gửi mail
            if (!empty($comment->Student->User->email)) {
                Mail::to($comment->Student->User->email)->send(new ReplyComment($comment, $request->email_content));
            }
            return response()->json([
                'status' => true
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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
    // mỗi chức năng đều chia ra controller riêng

    public function ajaxGetComments(){

        $user = $this->getUserLogin();
        $comments = DB::table('comments')
            ->leftJoin('students', 'students.id', '=', 'comments.created_by')
            ->leftJoin('users', 'students.user_id', '=', 'users.users_id')
            ->leftJoin('classes', 'classes.id', '=', 'students.class_id')
            ->select('comments.*', 'users.name as userName', 'classes.name as className');
        if ($user->Role->id >= ROLE_PHONGCONGTACSINHVIEN) // admin va phong ctsv thì lấy tất cả user
        {
            //  lấy hết. k cần điều kiện gì
        } else {
            $facultyId = $this->getUserLogin()->Faculty->id;
            $comments->where('users.faculty_id', $facultyId);
            if ($user->Role->id >= ROLE_BANCHUNHIEMKHOA) // chu nhiem khoa thì lấy user thuộc khoa giống
            {
                // lấy ra tất cả user cùng khoa vs user đang đăng nhập
            } elseif ($user->Role->id >= ROLE_COVANHOCTAP) { // cố vấn học tập
                $arrClassId = [];
                foreach ($user->Staff->Classes as $class) {
                    $arrClassId[] = $class->id;
                }
                $comments->whereIn('classes.id', $arrClassId);
            } else { // sinh viên. bán bộ
                $comments->where('comments.created_by', $user->Student->id);
            }
        }

        return DataTables::of($comments)
            ->addColumn('action', function ($comment) {
                $result = "";

                if($this->authorize('comment-reply')){
                    $linkShow = route('comment-show',$comment->id);
                    $linkReply = route('comment-reply',$comment->id);
                    if(empty($comment->reply)){
                        $title = "Phản hồi ý kiến";
                        $iTag = "<i class='fa fa-lg fa-check' aria-hidden='true'> </i>";
                        $class = "btn btn-primary comment-reply";
                    }else{
                        $title = "Đã phản hồi ý kiến";
                        $iTag = "<i class='fa fa-lg fa-edit' aria-hidden='true'> </i>";
                        $class = "btn btn-success comment-reply";
                    }
                    $buttonReply = "<a title='$title' class='$class' style='color: white' data-comment-id='$comment->id' data-comment-reply-link='$linkReply' data-comment-show-link='$linkShow'> $iTag </a>";
                    $result = $buttonReply." ";
                }

                if($this->authorize('comment-delete')){
                    $linkDelelte = route('comment-destroy',$comment->id);
                    $buttonDelete = "<a title='Xóa ý kiến' class='btn btn-danger comment-destroy' style='color: white' data-comment-id='$comment->id' data-comment-link='$linkDelelte' > <i class='fa fa-lg fa-trash-o' aria-hidden='true'> </i> </a>";
                    $result .= $buttonDelete." ";
                }

                $buttonShow = "<button title='Xem ý kiến' class='view-comment btn btn-info' style='color: white' data-id='$comment->id' link-view='$linkShow'> <i class='fa fa-eye' aria-hidden='true'> </i></button>";
                $result .= $buttonShow;
                return $result;
            })
            ->make(true);
    }
}
