@extends('layouts.master')
@section('title')
    STU| 403
@endsection
@section('content')
    <main>
        <div class="page-error">
            <h1><i class="fa fa-exclamation-circle"></i> Lỗi truy cập bị từ chối 403</h1>
            <p>Bạn không có quyền truy cập trang này.</p>
            <p><a class="btn btn-primary" href="javascript:window.history.back();"> Quay lại </a></p>
        </div>
    </main>
@endsection