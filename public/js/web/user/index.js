//            import
// $("#btn-import-student").click(function (e) {
$('body').on('click', '#btn-import-student', function (e) {
    e.preventDefault();
    $("#importModal").find("p.child-error").remove();
    var formData = new FormData();
    var fileImport = document.getElementById('fileImport');
    var inss = fileImport.files.length;
    for (var x = 0; x < inss; x++) {
        file = fileImport.files[x];
        formData.append("fileImport[]", file);
    }
    var url = $(this).attr('data-link');
    $('.form-row').find('span.messageErrors').remove();
    $.ajax({
        type: "post",
        url: url,
        data: formData,
        cache: false,
        contentType: false,
        // enctype: 'multipart/form-data',
        processData: false,
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
                            $('form#import-student-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
                if (result.errors !== undefined) {
                    // console.log(result.errors);
                    $('#importModal').find('div.alert-danger').show();
                    $.each(result.errors, function (elementName, arrMessagesEveryElement) {
                        $('#importModal').find('div.alert-danger').append("<p class='child-error'>" + arrMessagesEveryElement + "</p>");
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: " Upload Thành công ",
                    message: ":D",
                    icon: 'fa fa-check'
                }, {
                    type: "success"
                });
                $('div#importModal').modal('hide');
                oTable.draw();

            }
        }
    });
});

// $("button.update-user").click(function () {
$('body').on('click', 'button.update-user', function (e) {
    var urlEdit = $(this).attr('data-user-edit-link');
    var urlUpdate = $(this).attr('data-user-update-link');
    var id = $(this).attr('data-user-id');

    var modal = $('#modal-edit-user');
    modal.find('span.messageErrors').remove();
    $.ajax({
        type: "get",
        url: urlEdit,
        cache: false,
        data: {id: id},
        dataType: 'json',
        success: function (result) {
            if (result.status === true) {
                if (result.user !== undefined) {
                    var classId;
                    $.each(result.user, function (elementName, value) {
                        // alert(elementName + '- '+ value);
                        if (elementName === 'status' || elementName === 'role_id') {
                            modal.find("select." + elementName).val(value);
                        } else if (elementName === 'faculty_id') {
                            modal.find("select." + elementName).val(value);
                            getClassByFacultyId('modal-edit-user');
                        } else if (elementName === 'classes_id') {
                            classId = value;
                        } else if (elementName === 'gender') {
                            modal.find("." + elementName + "[value=" + value + "]").prop('checked', true);
                        } else {
                            modal.find('.' + elementName).val(value);
                        }
                    });
                    hideAndShowFaculty('modal-edit-user');
                    setTimeout(function () {
                        modal.find("select.classes_id").val(classId);
                    }, 800);
                }
            }
        }
    });
    modal.find(".modal-title").text('Sửa thông tài khoản');
    modal.find(".modal-footer > button[name=btn-save-user]").html('Sửa');
    modal.find(".modal-footer > button[name=btn-save-user]").attr('data-link', urlUpdate);
    modal.modal('show');
});

// $("#btn-save-user").click(function () {
$('body').on('click', '#btn-save-user', function (e) {

    var valueForm = $('form#user-form').serialize();
    var url = $(this).attr('data-link');
    $('#modal-edit-user').find('span.messageErrors').remove();

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
                            $('form#user-form').find('.' + elementName).parents('.form-group').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: " Sửa thành công ",
                    message: "",
                    icon: 'fa fa-check'
                }, {
                    type: "success"
                });
                $('div#modal-edit-user').modal('hide');
                oTable.draw();
            }
        }
    });
});


// $("button#btn-add").click(function () {
$('body').on('click', 'button#btn-add', function (e) {

    var valueForm = $('form#user-add-form').serialize();
    var url = $(this).attr('data-link');
    $('form#user-add-form').find('span.messageErrors').remove();
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
                            $('form#user-add-form').find('.' + elementName).parents('.col-md-8').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: " Thêm thành công",
                    message: ":D",
                    icon: 'fa fa-check'
                }, {
                    type: "success"
                });
                $('div#modal-add-user').modal('hide');
                oTable.draw();

            }
        }
    });
});