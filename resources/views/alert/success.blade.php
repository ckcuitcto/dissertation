<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 12-Apr-18
 * Time: 12:00 AM
 */
?>
@if(Session::has('flash_message'))
    <div class="alert alert-success">
        {!! Session::get('flash_message') !!}
    </div>
@endif
