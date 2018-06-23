
$('div.alert-success').delay(2000).slideUp();

$('body').on('click', 'a.comment-reply', function (e) {
    var urlShow = $(this).attr('data-comment-show-link');
    var urlReply = $(this).attr('data-comment-reply-link');
    var id = $(this).attr('data-faculty-id');
    $('.form-group').find('span.messageErrors').remove();
    $.ajax({
        type: "get",
        url: urlShow,
        data: {id: id},
        dataType: 'json',
        success: function (result) {
            if (result.status === true) {
                if (result.comment !== undefined) {
                    $.each(result.comment, function (elementName, value) {
                        if (elementName === 'title' || elementName === 'content') {
                            $('.' + elementName).html(value);
                        } else if (elementName === 'reply') {
                            $('.email_content').html(value);
                        } else {
                            $('.' + elementName).val(value);
                        }
                    });
                }
            }
        }
    });
    $('#myModal').find(".modal-footer > button[name=btn-reply]").attr('data-link', urlReply);
    $('#myModal').modal('show');
});

$('body').on('click', '#btn-reply', function (e) {
    var valueForm = $('form#reply-form').serialize();
    var url = $(this).attr('data-link');
    $('.form-group').find('span.messageErrors').remove();
    $.ajax({
        type: "post",
        url: url,
        data: valueForm,
        dataType: 'json',
        cache: false,
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
                            $('form#reply-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: "Thành công",
                    message: ":D",
                    icon: 'fa fa-check'
                }, {
                    type: "success"
                });
                $('div#myModal').modal('hide');
                oTable.draw();
            }
        }
    });
});

$('body').on('click', 'a.comment-destroy', function (e) {
    var id = $(this).attr("data-comment-id");
    var url = $(this).attr('data-comment-link');
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
                        swal("Deleted!", "Đã xóa góp ý !", "success");
                        $('.sa-confirm-button-container').click(function () {
                            oTable.draw();
                        })
                    } else {
                        swal("Cancelled", "Không tìm thấy góp ý !!! :)", "error");
                    }
                }
            });
        } else {
            swal("Đã hủy", "Đã hủy xóa:)", "error");
        }
    });
});

$('body').on('click', 'button.view-comment', function (e) {
    var url = $(this).attr('link-view');
    var id = $(this).attr('data-id');
    $.ajax({
        type: "get",
        url: url,
        data: {id: id},
        dataType: 'json',
        success: function (result) {
            if (result.status === true) {
                if (result.comment !== undefined) {
                    $.each(result.comment, function (elementName, value) {
                        $("div#modalViewComment").find('p.' + elementName).append(value);
                    });
                }
            }
        }
    });
    $('#modalViewComment').modal('show');
});

$('div#modalViewComment').on('hidden.bs.modal', function (e) {
    $('div#modalViewComment').find("p").html('');
});

$('#myModal').on('hidden.bs.modal', function (e) {
    $('#myModal').find("textarea.email_content").html('');
    $('.text-red').html('');
    $('span.messageErrors').remove();
});