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

$('body').on('click', 'button#btn-reply-remaking', function (e) {
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
                $.notify({
                    title: "Trả lời phúc khảo thành công ",
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

$('div#myModal').on('hidden.bs.modal', function (e) {
    $('div#myModal').find("span.remarking_reason").html('');
    $('.text-red').html('');
    $('span.messageErrors').remove();
});