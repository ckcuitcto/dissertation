<?php
/**
 * Created by PhpStorm.
 * User: Thai Duc
 * Date: 12-Apr-18
 * Time: 12:00 AM
 */
?>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

