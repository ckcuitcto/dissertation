$('#rolesTable').DataTable({
    "language": {
        "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
        "zeroRecords": "Không có bản ghi nào!",
        "info": "Hiển thị trang _PAGE_ của _PAGES_",
        "infoEmpty": "Không có bản ghi nào!!!",
        "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)",
        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Tải dữ liệu...</span>'
    },
    "pageLength": 25
});


$('#myModal').on('hidden.bs.modal', function (e) {
    $("input[type=text],input[type=number], select").val('');
    $("input[type=checkbox]").prop('checked', false);
    $('.text-red').html('');
    $('.form-group').find('span.messageErrors').remove();
    $(".name").prop('disabled',false);
});

$('body').on('click', 'a.role-destroy', function (e) {
    var id = $(this).attr("data-role-id");
    var url = $(this).attr('data-role-link');
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
                        swal("Deleted!", "Đã xóa vai trò " + data.role.name, "success");
                        $('.sa-confirm-button-container').click(function () {
                            location.reload();
                        })
                    } else {
                        swal("Cancelled", "Không tìm thấy Vai trò !!! :)", "error");
                    }
                }
            });
        } else {
            swal("Đã hủy", "Đã hủy xóa vai trò:)", "error");
        }
    });
});

$('body').on('click', '#btn-save-role', function (e) {
    var valueForm = $('form#role-form').serialize();
    var url = $(this).attr('data-link');
    $('span.messageErrors').remove();
    $.ajax({
        type: "post",
        url: url,
        data: valueForm,
        dataType: 'json',
        cache: false,
        success: function (result) {
            if (result.status === false) {
                //show error list fields
                if (result.arrMessages !== undefined) {
                    $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                        $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                            $('form#role-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $('#myModal').find('.modal-body').html('<p>Thành công</p>');
                $("#myModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                $('#myModal').on('hidden.bs.modal', function (e) {
                    location.reload(true);
                });
            }
        }
    });
});

$('body').on('click', 'a.role-update', function (e) {
    // $("a#role-update").click(function () {
    var urlEdit = $(this).attr('data-role-edit-link');
    var urlUpdate = $(this).attr('data-role-update-link');
    var id = $(this).attr('data-role-id');
    $('.form-group').find('span.messageErrors').remove();
    $.ajax({
        type: "get",
        url: urlEdit,
        data: {id: id},
        dataType: 'json',
        cache: false,
        success: function (result) {
            if (result.status === true) {
                if (result.role !== undefined) {
                    $.each(result.role, function (elementName, value) {
                        if(elementName === "permissions"){
                            $.each(value, function (permission, valuePermission) {
                                $('.permission_' + valuePermission.id).val(valuePermission.id).prop('checked', true);
                            });
                        }else{
                            $('.' + elementName).val(value);
                        }
                        $(".name").prop('disabled',true);
                    });
                }
            }
        }
    });
    $('#myModal').find(".modal-title").text('Sửa thông tin vai trò ');
    $('#myModal').find(".modal-footer > button[name=btn-save-role]").html('Sửa');
    $('#myModal').find(".modal-footer > button[name=btn-save-role]").attr('data-link', urlUpdate);
    $('#myModal').modal('show');
});