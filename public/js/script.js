function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imageChange').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}


$('#sampleTable').DataTable({
    "language": {
        "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
        "zeroRecords": "Không có bản ghi nào!",
        "info": "Hiển thị trang _PAGE_ của _PAGES_",
        "infoEmpty": "Không có bản ghi nào!!!",
        "infoFiltered": "(Đã lọc từ _MAX_ total bản ghi)"
    },
    "pageLength": 10
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var options = {
    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
};

<!--Khoa coppy-->

// $(function () {
//     $('img').bind('dragstart', function (event) {
//         event.preventDefault();
//     });
//     $('body').delegate("img").on("contextmenu", function () {
//         return false;
//     });
// });
// $(document).keydown(function (event) {
//     if (event.keyCode == 123) {
//         return false;
//     } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
//         return false;
//     }
// });


// if (document.layers) {
//     document.captureEvents(Event.MOUSEDOWN);

//     $(document).mousedown(function () {
//         return false;
//     });
// }
// else {
//     $(document).mouseup(function (e) {
//         if (e != null && e.type == "mouseup") {
//             //Check the Mouse Button which is clicked.
//             if (e.which == 2 || e.which == 3) {
//                 //If the Button is middle or right then disable.
//                 return false;
//             }
//         }
//     });
// }

// //Disable the Context Menu event.
// $(document).contextmenu(function () {
//     return false;
// });


$('body').on('click', '#btn-change-password', function (e) {
    var valueForm = $('form#change-password-form').serialize();
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
                            $('form#change-password-form').find('.' + elementName).parents('.form-row ').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                    if (result.arrMessages.message !== undefined) {
                        $('form#change-password-form').find('.current-password').parents('.form-row ').append('<span class="messageErrors" style="color:red">' + result.arrMessages.message + '</span>');
                    }
                }
            } else if (result.status === true) {
                $.notify({
                    title: "Đổi mật khẩu thành công",
                    message: ":D",
                    icon: 'fa fa-check'
                }, {
                    type: "success"
                });
                $('div#modalChangePassword').modal('hide');
            }
        }
    });
});