@extends('layouts.default')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-laptop"></i> Danh sách yêu cầu phúc khảo</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Danh sách yêu cầu phúc khảo</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <table id="remaking-table" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sinh viên</th>
                            <th>Lớp</th>
                            <th>Học kì</th>
                            <th>Năm học</th>
                            <th>Năm học</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Tác vụ</th>
                        </tr>
                        </thead>
                        {{--<tbody>--}}
                        {{--@foreach($remakings as $key =>  $request)--}}
                            {{--<tr>--}}
                                {{--<td>{{ $request->id }}</td>--}}
                                {{--<td>{{ $request->EvaluationForm->Student->User->name }}</td>--}}
                                {{--<td>{{ $request->EvaluationForm->Student->Classes->name }}</td>--}}
                                {{--<td> {{ $request->EvaluationForm->Semester->term }} </td>--}}
                                {{--<td> {{ $request->EvaluationForm->Semester->year_from."-".$request->EvaluationForm->Semester->year_to }} </td>--}}
                                {{--<td>{{  \App\Http\Controllers\Controller::getDisplayStatusRemaking($request->status)}}</td>--}}
                                {{--<td>{{  $request->created_at}}</td>--}}
                                {{--<td>--}}
                                    {{--@if($request->status !=  RESOLVED )--}}
                                        {{--<a class="btn btn-primary"--}}
                                           {{--href="{{ route('evaluation-form-show',[$request->EvaluationForm->id,'remaking=true&remaking_id='.$request->id]) }}">--}}
                                            {{--<i class="fa fa-edit" aria-hidden="true" style="color:white"></i> Chấm lại--}}
                                        {{--</a>--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Trả lời chấm phúc khảo</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="remarking-form">
                            {!! csrf_field() !!}
                            <div class="col-md-12">
                                <h3 class="tile-title">Lý do phúc khảo</h3>
                                <input type="hidden" class="id">
                                <div class="tile-body">
                                    <div class="form-group">
                                        <span class="remarking_reason"></span>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control remarking_reply" id="remarking_reply" rows="4" name="remarking_reply" placeholder="Nội dung trả lời chấm phúc khảo"></textarea>
                                    </div>
                                    <fieldset class="form-group">
                                        <legend>Trạng thái</legend>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input status" id="status" type="radio" name="status" value="{{HANDLE}}"> Đang xử lí
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input status" id="status" type="radio" name="status" value="{{RESOLVED}}"> Đã giải quyết
                                            </label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button data-link="" class="btn btn-primary"
                                    id="btn-reply-remaking" name="btn-reply-remaking" type="button">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i>Gửi
                            </button>
                            <button class="btn btn-secondary" id="closeForm" type="button" data-dismiss="modal">
                                <i class="fa fa-fw fa-lg fa-times-circle"></i>Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


@section('sub-javascript')
    <script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
    {{--<script type="text/javascript">$('#sampleTable').DataTable();</script>--}}
    <script>

        // $("button#btn-reply-remaking").click(function () {
        $('body').on('click', 'a#btn-reply-remaking-show', function (e) {
            var urlEdit = $(this).attr('data-remaking-edit-link');
            var urlUpdate = $(this).attr('data-remaking-update-link');
            var id = $(this).attr('data-remaking-id');
            $('.form-group').find('span.messageErrors').remove();
            $.ajax({
                type: "get",
                url: urlEdit,
                data: {id: id},
                dataType: 'json',
                success: function (result) {
                    if (result.status === true) {
                        if (result.remaking !== undefined) {
                            $.each(result.remaking, function (elementName, value) {
                                if(elementName === 'status') {
                                    $("form#remarking-form").find("." + elementName + "[value=" + value + "]").prop('checked', true);
                                }else if(elementName === 'remarking_reason'){
                                    $("form#remarking-form").find('.' + elementName).append(value);
                                } else{
                                    $("form#remarking-form").find('.' + elementName).val(value);
                                }
                            });
                        }
                    }
                }
            });
            $('#myModal').find(".modal-footer > button[name=btn-reply-remaking]").attr('data-link', urlUpdate);
            $('#myModal').modal('show');
        });

        $("button#btn-reply-remaking").click(function () {
            var valueForm = $('form#remarking-form').serialize();
            var url = $(this).attr('data-link');
            $('.form-group').find('span.messageErrors').remove();
            $.ajax({
                type: "post",
                url: url,
                data: valueForm,
                dataType: 'json',
                success: function (result) {
                    if (result.status === false) {
                        //show error list fields
                        if (result.arrMessages !== undefined) {
                            $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                                $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                                    if(elementName === 'status'){
                                        $('form#remarking-form').find('.' + elementName).parents('.form-check').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    }else{
                                        $('form#remarking-form').find('.' + elementName).parents('.form-group').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                                    }
                                });
                            });
                        }
                    } else if (result.status === true) {
                        $('div#myModal').find('.modal-body').html('<p> Trả lời phúc khảo thành công</p>');
                        $("div#myModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                        $('div#myModal').on('hidden.bs.modal', function (e) {
                            location.reload();
                        });
                    }
                }
            });
        });

        $('div#myModal').on('hidden.bs.modal', function (e) {
            $('div#myModal').find("span.remarking_reason").html('');
            $('.text-red').html('');
            $('span.messageErrors').remove();
        });

        $('#remaking-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('ajax-remakings') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "{{ csrf_token() }}"
                }
            },
            "columns": [
                {data: "userId", name: "students.user_id"},
                {data: "userName", name: "users.name"},
                {data: "className", name: "classes.name"},
                {data: "term", name: "semesters.term"},
                {data: "year_from", name: "semesters.year_from"},
                {data: "year_to", name: "semesters.year_to"},
                {data: "status", name: "remakings.status"},
                {data: "created_at", name: "remakings.created_at"},
                {data: 'action', name: 'action', orderable: false, searchable: false}

            ],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                // "zeroRecords": "Không có bản ghi nào!",
                // "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có bản ghi nào!!!",
                "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)"
            },
            "pageLength": 25
        });

        @if(!empty($userId))
            $('input[type="search"]').val("{{ $userId }}").keyup();
        @endif
    </script>
@endsection