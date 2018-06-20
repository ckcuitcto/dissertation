
    var oTable = $('#studentsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "ajax": {
            "url": "{{ route('ajax-get-students-by-class') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                "_token": "{{ csrf_token() }}"
            }
        },
        "columns": [
            {data: "userId", name: "userId"},
            {data: "userName", name: "userName"},
            {data: "userEmail", name: "userEmail"},
            // {data: "phone_number", name: "phone_number"},
            {data: "gender", name: "gender"},
            // {data: "address", name: "address"},
            // {data: "birthday", name: "birthday"},
            {data: "roleName", name: "roleName"},
            {data: "status", name: "status"},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "language": {
            "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
            // "zeroRecords": "Không có bản ghi nào!",
            // "info": "Hiển thị trang _PAGE_ của _PAGES_",
            "infoEmpty": "Không có bản ghi nào!!!",
            "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)"
        },
        "pageLength": 25
    });

    $("input#checkBoxChangePassword").change(function () {
        if($(this).val() === 'on'){
            $(this).val('off');
            $("input[type=password]").prop('disabled',false);
            $("div#changepassword").show();
        }else{
            $(this).val('on');
            $("input[type=password]").prop('disabled',true);
            $("div#changepassword").hide();

        }
    });

    $('body').on('click', '#btn-save-class', function (e) {
        // $("#btn-save-class").click(function () {
        var valueForm = $('form#class-form').serialize();
        var url = $(this).attr('data-link');
        $('#modal-edit-class').find('span.messageErrors').remove();
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
                                $('form#class-form').find('.' + elementName).parents('.form-group').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                            });
                        });
                    }
                } else if (result.status === true) {
                    $('#modal-edit-class').find('.modal-body').html('<p>Đã sửa thành công</p>');
                    $("#modal-edit-class").find('.modal-footer').html('<button  class="btn btn-default" data-dismiss="modal">Đóng</button>');
                    $('#modal-edit-class').on('hidden.bs.modal', function (e) {
                        location.reload();
                    });
                }
            }
        });
    });

    $('body').on('click', 'button.update-student', function (e) {
        // $("button.update-student").click(function () {
        var urlEdit = $(this).attr('data-student-edit-link');
        var urlUpdate = $(this).attr('data-student-update-link');
        var id = $(this).attr('data-student-id');
        $('#modal-edit-student').find('span.messageErrors').remove();
        $.ajax({
            type: "get",
            url: urlEdit,
            cache: false,
            dataType: 'json',
            success: function (result) {
                if (result.status === true) {
                    if (result.student !== undefined) {
                        $.each(result.student, function (elementName, value) {
                            // alert(elementName + '-' +value);
                            if(elementName === 'gender'){
                                $('#modal-edit-student').find("input[name=gender][value=" + value + "]").attr('checked', 'checked');
                            }else if(elementName === 'studentStatus' || elementName === 'role_id' ){
                                $('#modal-edit-student').find("select."+elementName).val(value);
                            }
                            else{
                                $('#modal-edit-student').find('.' + elementName).val(value);
                            }
                        });
                    }
                }
            }
        });
        $('#modal-edit-student').find(".modal-title").text('Sửa thông sinh viên');
        $('#modal-edit-student').find(".modal-footer > button[name=btn-save-student]").html('Sửa');
        $('#modal-edit-student').find(".modal-footer > button[name=btn-save-student]").attr('data-link', urlUpdate);
        $('#modal-edit-student').modal('show');
    });

    $('body').on('click', '#btn-save-student', function (e) {
        // $("#btn-save-student").click(function () {
        var valueForm = $('form#student-form').serialize();
        var url = $(this).attr('data-link');
        $('#modal-edit-student').find('span.messageErrors').remove();

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
                                $('form#student-form').find('.' + elementName).parents('.form-group').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                            });
                        });
                    }
                } else if (result.status === true) {
                    $.notify({
                        title: "Cập nhật thông tin thành công",
                        message: ":D",
                        icon: 'fa fa-check'
                    },{
                        type: "success"
                    });
                    $('div#modal-edit-student').modal('hide');
                    oTable.draw();

                }
            }
        });
    });
