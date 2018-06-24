// khi tìm kiếm. sẽ lưu lại giá trị học kì và khoa lại
$('body').on('submit', "form#search-form", function (e) {
    var form = $("form#search-form");
    $("form#export-students").find("input#semesterChoose").val(form.find("#semester_id").val());
    $("form#export-students").find("input#facultyChoose").val(form.find("#faculty_id").val());
    // $("form#export-students").find("input#facultyChoose").val(facultyId);
});

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
        $("form#export-students").find("input#strUsersId").val(strUsersId);
        $("form#export-students").find("input#strUserName").val(strUserName);
        $("form#export-students").find("input#strClassName").val(strClassName);
        $("form#export-students").submit();
    }
});