$('body').on('click', 'a.faculty-update', function (e) {
    var urlEdit = $(this).attr('data-faculty-edit-link');
    var urlUpdate = $(this).attr('data-faculty-update-link');
    var id = $(this).attr('data-faculty-id');
    $('.form-group').find('span.messageErrors').remove();
    $.ajax({
        type: "get",
        url: urlEdit,
        data: {id: id},
        dataType: 'json',
        success: function (result) {
            if (result.status === true) {
                if (result.faculty !== undefined) {
                    $.each(result.faculty, function (elementName, value) {
                        $('.' + elementName).val(value);
                    });
                }
            }
        }
    });
    $('#myModal').find(".modal-title").text('Sửa thông tin khoa');
    $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").html('Sửa')
    $('#myModal').find(".modal-footer > button[name=btn-save-faculty]").attr('data-link', urlUpdate);
    $('#myModal').modal('show');
});


$('body').on('click', '#btn-save-faculty', function (e) {
    var valueForm = $('form#faculty-form').serialize();
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
                            $('form#faculty-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: "Thành công  ",
                    message: ":D",
                    icon: 'fa fa-check'
                },{
                    type: "success"
                });
                $('div#myModal').modal('hide');
                oTable.draw();
            }
        }
    });
});

$('body').on('click', 'a.faculty-destroy', function (e) {
    var id = $(this).attr("data-faculty-id");
    var url = $(this).attr('data-faculty-link');
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
                        swal("Deleted!", "Đã xóa Khoa " + data.faculty.name, "success");
                        $('.sa-confirm-button-container').click(function () {
                            oTable.draw();
                        })
                    } else {
                        swal("Cancelled", "Không tìm thấy Khoa !!! :)", "error");
                    }
                }
            });
        } else {
            swal("Đã hủy", "Đã hủy xóa khoa:)", "error");
        }
    });
});