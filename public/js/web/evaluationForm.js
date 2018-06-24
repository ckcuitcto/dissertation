/**
 * Created by huynh on 06-May-18.
 */
$("input#child_1").change(function () {

    var arr = $("input#child_1");
    var total = 0;
    arr.each(function () {
        total += parseInt($(this).val());

    });
    var maxScore = $("input#total_1").attr('max');
    if (total > maxScore) {
        total = maxScore;
    }else if(total < 0){
        total = 0;
    }
    $("input#total_1").val(total);
    updatetotalScoreOfForm();
});

$("input#child_2").change(function () {

    var arr = $("input#child_2");
    var total = 0;
    arr.each(function () {
        total += parseInt($(this).val());

    });
    var maxScore = $("input#total_2").attr('max');
    if (total > maxScore) {
        total = maxScore;
    }else if(total < 0){
        total = 0;
    }
    $("input#total_2").val(total);
    updatetotalScoreOfForm();
});

$("input#child_3").change(function () {

    var arr = $("input#child_3");
    var total = 0;
    arr.each(function () {
        total += parseInt($(this).val());

    });
    var maxScore = $("input#total_3").attr('max');
    if (total > maxScore) {
        total = maxScore;
    }else if(total < 0){
        total = 0;
    }
    $("input#total_3").val(total);
    updatetotalScoreOfForm();
});

$("input#child_4").change(function () {

    var arr = $("input#child_4");
    var total = 0;
    arr.each(function () {
        total += parseInt($(this).val());

    });
    var maxScore = $("input#total_4").attr('max');
    if (total > maxScore) {
        total = maxScore;
    }else if(total < 0){
        total = 0;
    }
    $("input#total_4").val(total);
    updatetotalScoreOfForm();
});

$("input#child_5").change(function () {

    var arr = $("input#child_5");
    var total = 0;
    arr.each(function () {
        total += parseInt($(this).val());

    });
    var maxScore = $("input#total_5").attr('max');
    if (total > maxScore) {
        total = maxScore;
    }else if(total < 0){
        total = 0;
    }
    $("input#total_5").val(total);
    updatetotalScoreOfForm();
});


function updatetotalScoreOfForm() {
    var arr = $("[topic=totalTopic]");
    var total = 0;
    arr.each(function () {
        total += parseInt($(this).val());
    });

    if (total > 100) {
        total = 100;
    }else if(total < 0){
        total = 0;
    }
    $("input#totalScoreOfForm").val(total);
}

$('div.alert-success').delay(2000).slideUp();

$('#myModal').on('hidden.bs.modal', function (e) {
    $('#myModal').find('div#iframe-view-file').html('');
    $('#myModal').find("input[type=text],input[type=number], select").val('');
    $('form#proof-form').find('p.note-for-student').html('');
    $('span.messageErrors').remove();
    $('#myModal').find("#note").html('');
});

$('body').on('change', 'input.valid', function (e) {
    if ($(this).val() == 1) {
        $("form#proof-form").find('#textarea-note').hide();
    } else {
        $("form#proof-form").find('#textarea-note').show();
    }
});

$('body').on('submit', 'form#proof-form', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var url = $(this).attr("data-link");
    var proofId = formData.get('id');
    // console.log(formData.get('valid'));
    $('span.messageErrors').remove();
    $.ajax({
        type: "post",
        url: url,
        data: formData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {
            // console.log(result);
            if (result.status === true) {
                if(formData.get('valid') == 1){
                    $("i.proofId_"+proofId).removeClass('fa-times').addClass('fa-check');
                    $("i.proofId_"+proofId).parent().removeClass('btn-danger').addClass('btn-primary');
                }else{
                    $("i.proofId_"+proofId).removeClass('fa-check').addClass('fa-times');
                    $("i.proofId_"+proofId).parent().removeClass('btn-primary').addClass('btn-danger');
                }

                $('#myModal').modal('hide');
                $.notify({
                    title: "Cập nhật thành công : ",
                    message: ":D",
                    icon: 'fa fa-check'
                }, {
                    type: "info"
                });
            } else {
                $('form#proof-form').find('.note').parents('.form-group').append('<span class="messageErrors" style="color:red">' + result.arrMessages.note + '</span>');
            }
        }
    });
});