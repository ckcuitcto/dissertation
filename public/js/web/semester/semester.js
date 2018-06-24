$("input#year_from").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    todayBtn: "linked",
    clearBtn: true,
    language: "vi",
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true

});
$("input#year_to").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    todayBtn: "linked",
    clearBtn: true,
    language: "vi",
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true
});
$('input#date_start').datepicker({
    todayBtn: "linked",
    language: "vi",
    format: "dd/mm/yyyy",
    clearBtn: true,
    todayHighlight: true,
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true
});
$('input#date_end').datepicker({
    todayBtn: "linked",
    language: "vi",
    format: "dd/mm/yyyy",
    clearBtn: true,
    todayHighlight: true,
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true
});
$('input#date_start_to_mark').datepicker({
    todayBtn: "linked",
    language: "vi",
    format: "dd/mm/yyyy",
    clearBtn: true,
    todayHighlight: true,
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true
});
$('input#date_end_to_mark').datepicker({
    todayBtn: "linked",
    language: "vi",
    format: "dd/mm/yyyy",
    clearBtn: true,
    todayHighlight: true,
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true
});
$('input#date_end_to_re_mark').datepicker({
    todayBtn: "linked",
    language: "vi",
    format: "dd/mm/yyyy",
    clearBtn: true,
    todayHighlight: true,
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true
});
$('input#date_start_to_re_mark').datepicker({
    todayBtn: "linked",
    language: "vi",
    format: "dd/mm/yyyy",
    clearBtn: true,
    todayHighlight: true,
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true
});
$('input#date_start_to_request_re_mark').datepicker({
    todayBtn: "linked",
    language: "vi",
    format: "dd/mm/yyyy",
    clearBtn: true,
    todayHighlight: true,
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true
});
$('input#date_end_to_request_re_mark').datepicker({
    todayBtn: "linked",
    language: "vi",
    format: "dd/mm/yyyy",
    clearBtn: true,
    todayHighlight: true,
    orientation: "bottom right",
    autoclose: true,
    toggleActive: true
});


$('body').on('click', 'a.destroy-semester', function (e) {
    var id = $(this).attr("data-semester-id");
    var url = $(this).attr('data-semester-delete-link');
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
                        swal("Deleted! ", "Đã xóa học kì " + data.semester.term + " năm học " + data.semester.year_from + "-" + data.semester.year_to, "success");
                        $('.sa-confirm-button-container').click(function () {
                            oTable.draw();
                        })
                    } else {
                        swal("Cancelled", "Không tìm thấy học kì !!! :)", "error");
                    }
                }
            });
        } else {
            swal("Đã hủy", "Đã hủy xóa học kì:)", "error");
        }
    });
});
$('body').on('click', 'a.update-semester', function (e) {
    var urlEdit = $(this).attr('data-semester-edit-link');
    var urlUpdate = $(this).attr('data-semester-update-link');
    var id = $(this).attr('data-semester-id');
    $('.form-row').find('span.messageErrors').remove();
    $.ajax({
        type: "get",
        url: urlEdit,
        data: {id: id},
        dataType: 'json',
        success: function (result) {
            if (result.status === true) {
                if (result.semester !== undefined) {
                    $.each(result.semester, function (elementName, value) {
                        // alert(elementName + '- ' + value);
                        if (elementName === 'year_from' || elementName === 'year_to') {
                            $('.' + elementName).val(value).prop('disabled', true);
                        } else if (elementName === 'term') {
                            $('.' + elementName).val(value).prop('readonly', true);
                        } else {
                            $('.' + elementName).datepicker('setDate', value);
                        }
                    });
                }
                if (result.marktime !== undefined) {
                    $.each(result.marktime, function (elementName, value) {
                        var role_id = value.role_id;
                        $.each(value, function (messageType, messageValue) {
                            // alert(messageType + '-' + messageValue);
                            if (messageType === 'mark_time_start') {
                                $('.date_start_to_mark_' + role_id).datepicker('setDate', messageValue);
                            }
                            if (messageType === 'mark_time_end') {
                                $('.date_end_to_mark_' + role_id).datepicker('setDate', messageValue);
                            }
                        });
                    });
                }
            }
        }
    });
    $('#myModal').find(".modal-title").text('Sửa thông tin học kì');
    $('#myModal').find(".modal-footer > button[name=btn-save-semester]").html('Sửa');
    $('#myModal').find(".modal-footer > button[name=btn-save-semester]").attr('data-link', urlUpdate);
    $('#myModal').modal('show');
});

$('body').on('click', '#btn-save-semester', function (e) {
    var valueForm = $('form#semester-form').serialize();
    var url = $(this).attr('data-link');
    $('.form-row').find('span.messageErrors').remove();
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
                            $('form#semester-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: "Thêm học kì thành công",
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


