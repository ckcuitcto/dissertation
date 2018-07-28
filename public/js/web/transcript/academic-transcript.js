$('body').on('click', "button#createFile", function (e) {

    var strUsersId = new Array();
    var strUserName = new Array();
    var strClassName = new Array();
    oTable.rows().every( function () {
        var userId = this.data().users_id;
        var userName = this.data().userName;
        var className = this.data().className;

        strUsersId.push(userId);
        strUserName.push(userName);
        strClassName.push(className);
    });
    if(strUsersId.length === 0 || strUserName.length === 0 || strClassName.length === 0){
        $.notify({
            title: "Lưu ý: ",
            message: "Danh sách rỗng",
            icon: 'fa fa-exclamation-triangle'
        },{
            type: "danger"
        });
    }else{
        $("form#export-academic-transcript").find("input.strUsersId").val(strUsersId);
        $("form#export-academic-transcript").find("input.strUserName").val(strUserName);
        $("form#export-academic-transcript").find("input.strClassName").val(strClassName);

        var dataForm = $("form#export-academic-transcript").serialize();
        var url = $("form#export-academic-transcript").attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            data: dataForm,
            beforeSend: function(){
                $("#ajax_loader").show();
            },
            success: function (data) {
                $("#ajax_loader").hide();
                if (data.status === true) {
                    if(data.file_path !== undefined){
                        var a = document.createElement('a');
                        a.href = data.file_path;
                        a.download = data.file_name;
                        a.click();
                    }
                } else {
                    swal("Không thành công", data.message +" !!! :)", "error");
                }
            }
        });
    }
});


$('body').on('click', "button#createFileResultAllCourse", function (e) {

    var strUsersId = new Array();
    var strUserName = new Array();
    var strClassName = new Array();
    oTable.rows().every( function () {
        var userId = this.data().users_id;
        var userName = this.data().userName;
        var className = this.data().className;

        strUsersId.push(userId);
        strUserName.push(userName);
        strClassName.push(className);
    });
    if(strUsersId.length === 0 || strUserName.length === 0 || strClassName.length === 0){
        $.notify({
            title: "Lưu ý: ",
            message: "Danh sách rỗng",
            icon: 'fa fa-exclamation-triangle'
        },{
            type: "danger"
        });
    }else{
        $("form#export-academic-transcript-all-course").find("input.strUsersId").val(strUsersId);
        $("form#export-academic-transcript-all-course").find("input.strUserName").val(strUserName);
        $("form#export-academic-transcript-all-course").find("input.strClassName").val(strClassName);

        var dataForm = $("form#export-academic-transcript-all-course").serialize();
        var url = $("form#export-academic-transcript-all-course").attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            data: dataForm,
            beforeSend: function(){
                $("#ajax_loader").show();
            },
            success: function (data) {
                $("#ajax_loader").hide();
                if (data.status === true) {
                    if(data.file_path !== undefined){
                        var a = document.createElement('a');
                        a.href = data.file_path;
                        a.download = data.file_name;
                        a.click();
                    }
                } else {
                    swal("Không thành công", data.message +" !!! :)", "error");
                }
            }
        });
    }
});

$('body').on('click', 'a.update-academic-transcript', function (e) {
    var urlEdit = $(this).attr('data-edit-link');
    var urlUpdate = $(this).attr('data-update-link');
    var id = $(this).attr('data-semester-id');
    $('.form-row').find('span.messageErrors').remove();
    $.ajax({
        type: "get",
        url: urlEdit,
        data: {id: id},
        dataType: 'json',
        success: function (result) {
            if (result.status === true) {
                if (result.academicTranscript !== undefined) {
                    var add_class_id;
                    var add_faculty_id;
                    var add_semester_id;
                    var add_student_id;
                    $.each(result.academicTranscript, function (elementName, value) {
                        if (elementName === 'add_class_id') {
                            $("select#add_class_id").empty().append('<option value="' + value + '">' + result.academicTranscript.add_class_name + '</option>').attr('readonly',true);
                        } else if (elementName === 'add_faculty_id') {
                            // $("select#add_faculty_id").empty().append('<option value="' + value + '">' + result.academicTranscript.add_faculty_name + '</option>').attr('readonly',true);
                            $("select#add_faculty_id").val(value).attr('disabled',true);
                        } else if (elementName === 'add_semester_id') {
                            $("#add_semester_id").val(value);
                        } else if (elementName === 'add_student_id') {
                            $("select#add_student_id").empty().append('<option value="' + value + '">' + result.academicTranscript.add_student_name + '</option>').attr('readonly',true);
                        } else if (elementName === 'note') {
                            $("textarea#"+elementName).html(value);
                        } else {
                            $('.' + elementName).val(value);
                        }
                    });
                }
            }
        }
    });
    $('#myModal').find(".modal-title").text('Sửa điểm');
    $('#myModal').find(".modal-footer > button[name=academic-transcript-store]").html('Sửa');
    $('#myModal').find(".modal-footer > button[name=academic-transcript-store]").attr('data-link', urlUpdate);
    $('#myModal').modal('show');
});

$('#myModal').on('hidden.bs.modal', function (e) {
    $("select.add_faculty_id").trigger('change');
    $('#myModal').find("input[type=text],input[type=number], select").attr('readonly', false).attr('disabled', false);
    $('#myModal').find("input[type=text],input[type=number], textarea").val('');
    $('.text-red').html('');
    $('span.messageErrors').remove();
    $('#myModal').find(".modal-title").text('Thêm mới điểm cho sinh viên');
    $('#myModal').find(".modal-footer > button[name=academic-transcript-store]").html('Thêm');
});

