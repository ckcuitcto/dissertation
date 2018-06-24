$('input#birthday').datepicker({
    todayBtn: "linked",
    language: "vi",
    format: "dd/mm/yyyy",
    clearBtn: true,
    orientation: "bot right",
    autoclose: true,
    toggleActive: true,
});

$('body').on('click', 'img#imageChange', function (e) {
    $("input#avatar").click();
});

$('body').on('click', 'a#btn-update-inform', function (e) {
    $(".button-edit").show();
    $(".can-edit").removeAttr('disabled');
    $("a#btn-update-inform").hide();
});

$('body').on('click', 'a#btn-cancel-inform', function (e) {
    $(".button-edit").hide();
    $(".can-edit").prop('disabled', true);
    $("a#btn-update-inform").show();
    $('span.messageErrors').remove();
});
