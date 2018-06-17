@extends('layouts.default')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-o"></i> Quản lý minh chứng</h1>
                <p>Trường Đại học Công nghệ Sài Gòn</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item"> Quản lý minh chứng</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <section class="invoice">
                        <div class="tile-body">
                            <table class="table table-hover table-bordered" id="sampleTable">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tiêu chí</th>
                                    <th>File</th>
                                    <th>Học kỳ</th>
                                    <th>Năm học</th>
                                    <th>Trạng thái</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($proofList as $key =>  $proof)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$proof->content}}</td>
                                        <td>
                                            <a style="color:white" data-proof-id="{{$proof->id}}"
                                               id="proof-view-file"
                                               data-proof-file-path="{{ asset('upload/proof/'.$proof->name) }}"
                                               data-link-update-proof-file="{{ route('proof-destroy',$proof->id ) }}"
                                               data-get-file-link="{{route('evaluation-form-get-file',$proof->id)}}" class="btn btn-primary">
                                                <i class="fa fa-eye"
                                                   aria-hidden="true"></i>{{ str_limit($proof->name,20) }}
                                            </a>
                                        </td>
                                        <td>{{$proof->term}}</td>
                                        <td>{{$proof->year_from. "-".$proof->year_to}}</td>
                                        <td>{{ ($proof->valid) ? "Hợp lệ" : "Không hợp lệ" }}</td>
                                        <td>
                                            @if(\App\Http\Controllers\Proof\ProofController::checkTimeMark($proof->mark_time_start,$proof->mark_time_end))
                                                <button type="button" class="btn btn-danger"
                                                        data-proof-id="{{$proof->id}}" id="proof-destroy"
                                                        data-proof-link="{{route('proof-destroy',$proof->id)}}">
                                                    <i class="fa fa-lg fa-trash"></i> Xóa
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-outline-success" id="createFile">
                                    <i class="fa fa-plus" aria-hidden="true"></i>Thêm minh chứng
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="overlay">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Xem file minh chứng</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <div id="iframe-view-file" class="doc"></div>
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
        $(document).ready(function () {

            $("a#proof-view-file").click(function (e) {
                var thisproof = $(this);
                var name = thisproof.attr('data-proof-file-path');
                var id = thisproof.attr('data-proof-id');
                var url = thisproof.attr('data-get-file-link');
                var urlDeleteProofFile = thisproof.attr('data-link-update-proof-file');

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    success: function (data) {
                        if (data.status === true && data.proof !== undefined) {
                            $("form#proof-form").attr("data-link", urlDeleteProofFile);
                            $.each(data.proof, function (elementName, value) {
                                if (elementName === 'name') {
                                    var urlFile = '{{ asset("upload/proof/") }}' + '/' + value;
                                    var contentView = '<iframe class="doc" src="' + urlFile + '"></iframe>';
                                    $('div#iframe-view-file').html(contentView);
                                }
                            });
                            $('#myModal').modal('show');
                        }
                    }
                });
            });

            $('button#proof-destroy').click(function () {
                var id = $(this).attr("data-proof-id");
                var url = $(this).attr('data-proof-link');
                swal({
                    title: "Bạn chắc chưa?",
                    text: "Bạn sẽ không thể khôi phục lại dữ liệu !!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Có, tôi chắc chắn!",
                    cancelButtonText: "Không, Hủy dùm tôi!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: url,
                            type: 'GET',
                            cache: false,
                            data: {"id": id},
                            success: function (data) {
                                if (data.status === true) {
                                    swal("Deleted!", "Đã xóa file " + data.proof.name, "success");
                                    $('.sa-confirm-button-container').click(function () {
                                        location.reload();
                                    })
                                } else {
                                    swal("Cancelled", "Không tìm thấy file !!! :)", "error");
                                }
                            }
                        });
                    } else {
                        swal("Đã hủy", "Đã hủy xóa File:)", "error");
                    }
                });
            });

        });
    </script>

    <script type="text/javascript">
        $('#demoSwal').click(function () {
            swal({
                title: "Bạn có chắc muốn xóa?",
                text: "Bạn sẽ không khôi phục được hành động này.",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes!",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    swal("Deleted!", "Đã xóa sinh viên.", "success");
                } else {
                    swal("Cancelled", "Mọi thứ an toàn :)", "error");
                }
            });
        });
    </script>
@endsection