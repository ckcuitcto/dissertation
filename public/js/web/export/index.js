
$('#search-form').on('submit', function(e) {
    oTable.draw();
    e.preventDefault();
});

$('body').on('change', "select#semester_id", function (e) {
    $("input#semesterChoose").val($(this).val());
});

$('body').on('click', 'input[name=checkAll]', function (e) {
    if($(this).is(':checked')){
        $("input.checkboxClasses").prop('checked', true);
    }else{
        $("input.checkboxClasses").prop('checked', false);
    }
});

$('body').on('change', "input.checkboxClasses", function (e) {
    $("input[name=checkAll]").prop('checked',false);
});


$('body').on('click', 'button#btnExport', function (e) {
    var boxes = $('input.checkboxClasses:checked');
    if(boxes.length == 0){
        $.notify({
            title: "Vui lòng chọn lớp !!!!!!",
            message: ":(",
            icon: 'fa fa-exclamation-triangle'
        },{
            type: "warning"
        });
    }else{
        $("form#class-form-export").find("#withDiscipline").val("no");
        $("form#class-form-export").submit();
    }
});

$('body').on('click', 'button#btnExportWithDiscipline', function (e) {
    var boxes = $('input.checkboxClasses:checked');
    if(boxes.length == 0){
        $.notify({
            title: "Vui lòng chọn lớp !!!!!!",
            message: ":(",
            icon: 'fa fa-exclamation-triangle'
        },{
            type: "warning"
        });
    }else{
        $("form#class-form-export").find("#withDiscipline").val("yes");
        $("form#class-form-export").submit();
    }
});
$('div.alert-success').delay(2000).slideUp();