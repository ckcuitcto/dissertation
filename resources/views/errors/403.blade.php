@extends('layouts.master')
@section('content')
    <main>
        <div class="page-error tile">
            <h1><i class="fa fa-exclamation-circle"></i> Access Denied Error 403</h1>
            <p>The requested resource requires an authentication.</p>
            <p><a class="btn btn-primary" href="javascript:window.history.back();">Go Back</a></p>
        </div>
    </main>

@endsection