$('body').on('click', "button#btn-request-remaking", function (e) {
    var evaluationFormId = $(this).attr('data-id-evaluation-form');
    $("form#remarking-form").find("input#evluationFormId").val(evaluationFormId);
});

$('body').on('click', 'button#btn-send-remaking', function (e) {
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
                            $('form#remarking-form').find('.' + elementName).parents('.form-group').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $('div#myModal').find('.modal-body').html('<p>Gửi yêu cầu phúc khảo thành công</p>');
                $("div#myModal").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                $('div#myModal').on('hidden.bs.modal', function (e) {
                    location.reload();
                });
            }
        }
    });
});