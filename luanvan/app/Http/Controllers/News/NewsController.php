<?php

namespace App\Http\Controllers\News;

use App\Model\Faculty;
use App\Model\News;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Yajra\DataTables\DataTables;

class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('aut thêh');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('news.index');
    }

    public function create()
    {
        $userLogin = $this->getUserLogin();
        if($userLogin->Role->weight == ROLE_PHONGCONGTACSINHVIEN OR $userLogin->Role->weight == ROLE_ADMIN){
            $faculties = Faculty::all()->toArray();
            $faculties = array_prepend($faculties,array('id' => 0,'name' => 'Tất cả khoa'));
        }else{
            $faculties = Faculty::where('id',$userLogin->Faculty->id)->get()->toArray();
        }
        return view('news.add', compact('faculties'));
    }

    public function show($title,$id)
    {
        if(!is_numeric($id)){
            $id = explode('-',$id);
            $id = $id[count($id)-1];
        }
        $news = News::find($id);
        if(!empty($news)){
            return view('news.show', compact('news'));
        }
        return redirect()->back();
    }

    public function edit($id)
    {
        $news = News::find($id);
        if(!empty($news)){
            $userLogin = $this->getUserLogin();
            if($userLogin->Role->weight == ROLE_PHONGCONGTACSINHVIEN OR $userLogin->Role->weight == ROLE_ADMIN){
                $faculties = Faculty::all()->toArray();
                $faculties = array_prepend($faculties,array('id' => 0,'name' => 'Tất cả khoa'));
            }else{
                $faculties = Faculty::where('id',$userLogin->Faculty->id)->get()->toArray();
            }
            return view('news.edit', compact('faculties','news'));
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,
            [
                'title' => 'required',
                'news_content' => 'required',
            ],
            [
                'title.required' => "Vui lòng nhập tiêu đề",
                'news_content.required' => 'Vui lòng nhập nội dung',
            ]
        );

        $news = News::find($id);
        if (!empty($news)) {
            $news->title = $request->title;
            $news->content = $request->news_content;
            $news->faculty_id = $request->faculty_id;
            $news->save();
            return redirect()->route('news')->with('success','Sửa tin tức thành công!');
        }
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'title' => 'required',
                'news_content' => 'required',
            ],
            [
                'title.required' => "Vui lòng nhập tiêu đề",
                'news_content.required' => 'Vui lòng nhập nội dung',
            ]
        );
        $news = new News();
        $news->title = $request->title;
        $news->content = $request->news_content;
        if($request->faculty_id) {
            $news->faculty_id = $request->faculty_id;
        }
        if(!empty($this->getUserLogin()->Staff->id)){
            $news->created_by =$this->getUserLogin()->Staff->id;
        }else{
            $news->created_by =$this->getUserLogin()->Student->id;
        }

        $news->save();

        return redirect()->route('news')->with('success','Thêm tin tức thành công!');
    }

    public function destroy($id)
    {
        $news = News::find($id);
        if (!empty($news)) {
            $news->delete();
            return response()->json([
                'news' => $news,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }

    public function ajaxGetNews(Request $request){
        $userLogin = $this->getUserLogin();

        if ($userLogin->Role->weight >= ROLE_PHONGCONGTACSINHVIEN) // admin va phong ctsv thì lấy tất cả user
        {
            $newsList = DB::table('news')->select('news.*');
        } elseif ($userLogin->Role->weight == ROLE_BANCHUNHIEMKHOA OR $userLogin->Role->weight == ROLE_COVANHOCTAP ) {
            // neeus laf ban chu nhiem khoa thi lay cung khoa
            $newsList = DB::table('users')
                ->leftJoin('staff', 'users.users_id', '=', 'staff.user_id')
                ->rightJoin('news', 'news.created_by', '=', 'staff.id')
                ->where('users.faculty_id', $userLogin->faculty_id)
                ->select('news.*');
        }

        return DataTables::of($newsList)
            ->addColumn('action', function ($news) {
                $newsId = $news->id;
                $linkEdit = route('news-edit',$newsId);
                $linkDestroy = route('news-destroy',$newsId);
                $linkView = route('news-show',[ str_slug($news->title),$newsId]);
                $linkView= "<a href='$linkView' class='btn btn-primary'> <i class='fa fa-eye'></i> </a>";


                $buttonEdit = "<a class='btn btn-primary' href='$linkEdit'><i class='fa fa-lg fa-edit'></i></a>";

                $linkButton = "<button type='button' class='btn btn-danger news-destroy' 
                                data-news-link='$linkDestroy'
                                data-news-id='$newsId'><i class='fa fa-lg fa-trash' aria-hidden='true'></i>
                                </button>";
                return "<p class='bs-component'>$linkView $buttonEdit $linkButton</p>";
            })
            ->make(true);
    }
}
