<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 06-Apr-18
 * Time: 9:56 PM
 */
?>
@extends('layouts.default')
@section('content')
    <main class="app-content">
        <div class="page-error tile">
            <h1><i class="fa fa-exclamation-circle"></i> Error 404: Page not found</h1>
            <p>The page you have requested is not found.</p>
            <p><a class="btn btn-primary" href="javascript:window.history.back();">Go Back</a></p>
        </div>
    </main>
@endsection
