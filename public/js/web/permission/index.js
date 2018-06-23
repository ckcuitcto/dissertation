$('body').on('click', 'a.permission-update', function (e) {

    var urlEdit = $(this).attr('data-permission-edit-link');
    var urlUpdate = $(this).attr('data-permission-update-link');
    var id = $(this).attr('data-permission-id');
    $('.form-row').find('span.messageErrors').remove();
    $.ajax({
        type: "get",
        url: urlEdit,
        data: {id: id},
        dataType: 'json',
        success: function (result) {
            if (result.status === true) {
                if (result.permission !== undefined) {
                    $.each(result.permission, function (elementName, value) {
                        if(elementName === 'name'){
                            $('.' + elementName).val(value).prop('disabled',true);
                        }else{
                            $('.' + elementName).val(value);
                        }
                    });
                }
            }
        }
    });
    $('#myModal').find(".modal-title").text('Sửa thông tin quyền');
    $('#myModal').find(".modal-footer > button[name=btn-save-permission]").html('Sửa')
    $('#myModal').find(".modal-footer > button[name=btn-save-permission]").attr('data-link', urlUpdate);
    $('#myModal').modal('show');
});

$('body').on('click', '#btn-save-permission', function (e) {
    // $("#btn-save-permission").click(function () {
    var valueForm = $('form#permission-form').serialize();
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
                            $('form#permission-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: "Thành công",
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

$('#myModal').on('hidden.bs.modal', function (e) {
    $("input[type=text],input[type=number],input[type=hidden], select").val('').prop('disabled',false);
    $('.text-red').html('');
    $('.form-group').find('span.messageErrors').remove();
});