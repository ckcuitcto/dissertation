@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Thêm tin tức</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Thêm tin tức</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                <form id="news-form" action="{{ route('news-store') }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="faculty_id">Khoa</label>
                                <select class="form-control faculty_id" name="faculty_id" id="faculty_id">
                                    @foreach($faculties as $value)
                                        <option value="{{ $value['id'] }}">{{ $value['name']}}</option>
                                    @endforeach
                                </select>
                                <p style="color:red; display: none;" class="faculty_id"></p>
                            </div>
                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input type="hidden" name="id" class="id" id="idNewsModal">
                                <input class="form-control title" id="title" name="title" type="text" required
                                       aria-describedby="news" placeholder="Nhập tiêu đề">
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="news_content">Nội dung</label>
                                <textarea class="form-control news_content" id="news_content" name="news_content" required aria-describedby="news" >
                                        </textarea>
                                @if ($errors->has('news_content'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('news_content') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-link="{{ route('news-store') }}" class="btn btn-primary"
                                id="btn-save-news" name="btn-save-news" type="submit">
                            Thêm
                        </button>
                        <a class="btn btn-secondary" href="{{ route('news') }}" data-dismiss="modal">Đóng</a>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </main>
@endsection

@section('sub-javascript')
    <script>
        CKEDITOR.replace('news_content',options);
        $(document).ready(function () {
        });
    </script>

@endsection
