// $('#importModal').on('hidden.bs.modal', function (e) {
//     $("input[type=text],input[type=number], select").val('');
//     $('.text-red').html('');
//     $('.form-row').find('span.messageErrors').remove();
// });
//
//
// $('#search-form').on('submit', function (e) {
//     oTable.draw();
//     e.preventDefault();
// });
//
//
// //            import
//
// $("#btn-import-student").click(function (e) {
//     e.preventDefault();
//     $('.form-row').find('span.messageErrors').remove();
//     var $fileUpload = $("input[type='file']");
//     if (parseInt($fileUpload.get(0).files.length) > 20) {
//         $('form#import-student-form').find('.fileImport').parents('.form-row').append('<span class="messageErrors" style="color:red">Chỉ được upload tối đa 20 tập tin</span>');
//     } else {
//         $('#importModal').find('.show-error').hide();
//         $("#importModal").find("p.child-error").remove();
//         var formData = new FormData();
//         var fileImport = document.getElementById('fileImport');
//         var inss = fileImport.files.length;
//         for (var x = 0; x < inss; x++) {
//             file = fileImport.files[x];
//             formData.append("fileImport[]", file);
//         }
//         var url = $(this).attr('data-link');
//         $('.form-row').find('span.messageErrors').remove();
//         $.ajax({
//             type: "post",
//             url: url,
//             data: formData,
//             cache: false,
//             contentType: false,
//             // enctype: 'multipart/form-data',
//             processData: false,
//             beforeSend: function () {
//                 $('#ajax_loader').show();
//             },
//             success: function (result) {
//                 $('#ajax_loader').hide();
//                 $("#importModal").find("button#btn-import-student").prop('disabled', false);
//                 if (result.status === false) {
//                     //show error list fields
//                     if (result.arrMessages !== undefined) {
//                         $.each(result.arrMessages, function (elementName, arrMessagesEveryElement) {
//                             $.each(arrMessagesEveryElement, function (messageType, messageValue) {
//                                 $('form#import-student-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
//                             });
//                         });
//                     }
//                     if (result.errors !== undefined) {
//                         // console.log(result.errors);
//                         $('#importModal').find('div.alert-danger').show();
//                         $.each(result.errors, function (elementName, arrMessagesEveryElement) {
//                             $('#importModal').find('div.alert-danger').append("<p class='child-error'>" + arrMessagesEveryElement + "</p>");
//                         });
//                     }
//                 } else if (result.status === true) {
//                     $.notify({
//                         title: "Upload Thành công ",
//                         message: ":D",
//                         icon: 'fa fa-check'
//                     },{
//                         type: "success"
//                     });
//                     $('div#importModal').modal('hide');
//                     oTable.draw();
//                 }
//             }
//         });
//     }
// });