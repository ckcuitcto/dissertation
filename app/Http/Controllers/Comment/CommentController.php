<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Comment;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comment = Comment::all();
        return view('comment.index',compact('comment'));
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
    
        return redirect()->back()->with('success','Gửi ý kiến thành công!');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // cái edit này cũng giống show. a chưa phân biệt đc lắm.
    public function edit($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
        //
    }

    // mỗi chức năng đều chia ra controllẻ riêng nên chắc đủ hàm r. k cần tạo thêm
}
