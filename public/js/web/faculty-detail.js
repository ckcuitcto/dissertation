
$('body').on('click', 'input[name=checkAll]', function (e) {
    if ($(this).is(':checked')) {
        $("input.checkboxClasses").prop('checked', true);
    } else {
        $("input.checkboxClasses").prop('checked', false);
    }
});

$('body').on('change', "input.checkboxClasses", function (e) {
    $("input[name=checkAll]").prop('checked', false);
});


$('div.alert-success').delay(2000).slideUp();


$('body').on('click', 'a.class-edit', function (e) {
    var urlEdit = $(this).attr('data-edit-link');
    var urlUpdate = $(this).attr('data-update-link');
    var id = $(this).attr('data-id');
    $('.form-group').find('span.messageErrors').remove();
    $.ajax({
        type: "get",
        url: urlEdit,
        data: {id: id},
        dataType: 'json',
        success: function (result) {
            if (result.status === true) {
                if (result.classes !== undefined) {
                    $.each(result.classes, function (elementName, value) {
                        $('.' + elementName).val(value);
                    });
                }
            }
        }
    });
    $('#myModal').find(".modal-title").text('Sửa thông tin lớp ');
    $('#myModal').find(".modal-footer > button[name=btn-save-class]").html('Sửa');
    $('#myModal').find(".modal-footer > button[name=btn-save-class]").attr('data-link', urlUpdate);
    $('#myModal').modal('show');
});

$('body').on('click', '#btn-save-class', function (e) {
    var valueForm = $('form#class-form').serialize();
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
                            $('form#class-form').find('.' + elementName).parents('.form-group ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: "Thành công  ",
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

$('body').on('click', 'a.class-destroy', function (e) {
    // $('a#class-destroy').click(function () {
    var id = $(this).attr("data-id");
    var url = $(this).attr('data-link');
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
                        swal("Deleted!", "Đã xóa lớp " + data.class.name, "success");
                        $('.sa-confirm-button-container').click(function () {
                            oTable.draw();
                        })
                    } else {
                        swal("Cancelled", "Không tìm thấy lớp !!! :)", "error");
                    }
                }
            });
        } else {
            swal("Đã hủy", "Đã hủy xóa lớp:)", "error");
        }
    });
});

$('#myModal').on('hidden.bs.modal', function (e) {
    $('#myModal').find("input[type=text],input[type=number], select").val('');
    $('.text-red').html('');
    $('span.messageErrors').remove();
    $('#myModal').find(".modal-title").text("Thêm mới lớp thuộc khoa {{ $faculty->name }}");
    $('#myModal').find(".modal-footer > button[name=btn-save-class]").html('Thêm');
    $('#myModal').find(".modal-footer > button[name=btn-save-class]").attr('data-link', "{{ route('class-store') }}");
});

