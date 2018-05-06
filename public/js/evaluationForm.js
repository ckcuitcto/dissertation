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
    }
    $("input#total_5").val(total);
    updatetotalScoreOfForm();
});


// $("[topic=totalTopic]").change(function () {
//     alert(1);
//     var arr = $("[topic=totalTopic]");
//     var total = 0;
//     arr.each(function () {
//         total += parseInt($(this).val());
//
//     });
//
//     if (total > 100) {
//         total = 100;
//     }
//     $("input#totalScoreOfForm").val(total);
// });

function updatetotalScoreOfForm() {
    var arr = $("[topic=totalTopic]");
    var total = 0;
    arr.each(function () {
        total += parseInt($(this).val());
    });

    if (total > 100) {
        total = 100;
    }
    $("input#totalScoreOfForm").val(total);
}