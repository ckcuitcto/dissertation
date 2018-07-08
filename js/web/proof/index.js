$('body').on('click', 'button#btn-update-proof', function (e) {
    var valueForm = $('form#update-proof-form').serialize();
    var url = $(this).attr('data-link');
    $('.form-row').find('span.messageErrors').remove();
    $.ajax({
        type: "post",
        url: url,
        data: valueForm,
        dataType: 'json',
        beforeSend: function () {
            $('#ajax_loader').show();
        },
        success: function (result) {
            $('#ajax_loader').hide();
            if (result.status === false) {
                //show error list fields
                if (result.arrMessages !== undefined) {
                    $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                        $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                            $('form#update-proof-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {

                $.notify({
                    title: "Sửa minh chứng thành công",
                    message: ":D",
                    icon: 'fa fa-check'
                }, {
                    type: "success"
                });
                $('div#updateModal').modal('hide');
                oTable.draw();
            }
        }
    });
});

$('body').on('click', 'button#proof-destroy', function (e) {
    var id = $(this).attr("data-proof-id");
    var url = $(this).attr('data-proof-destroy-link');

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
                            oTable.draw();
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

$('body').on('click', 'button#btn-upload-proof', function (e) {
    e.preventDefault();
    var formData = new FormData($('form#upload-proof-form')[0]);
    var fileUpload = document.getElementById('fileUpload');
    var inss = fileUpload.files.length;
    for (var x = 0; x < inss; x++) {
        file = fileUpload.files[x];
        formData.append("fileUpload[]", file);
    }
    var url = $(this).attr('data-link');
    $('.form-row').find('span.messageErrors').remove();
    $.ajax({
        type: "post",
        url: url,
        data: formData,
        processData: false,
        dataType: 'json',
        contentType: false,
        enctype: 'multipart/form-data',
        beforeSend: function () {
            $('#ajax_loader').show();
        },
        success: function (result) {
            $('#ajax_loader').hide();
            if (result.status === false) {
                //show error list fields
                if (result.arrMessages !== undefined) {
                    $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
                        $.each(arrMessagesEveryElement, function (messageType, messageValue) {
                            $('form#upload-proof-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: "Thêm minh chứng thành công",
                    message: ":D",
                    icon: 'fa fa-check'
                }, {
                    type: "success"
                });
                $('div#addModal').modal('hide');
                oTable.draw();
            }
        }
    });
});