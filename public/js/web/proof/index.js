$('body').on('click', 'button#btn-update-proof', function (e) {
    var valueForm = $('form#update-proof-form').serialize();
    var url = $(this).attr('data-link');
    $('.form-row').find('span.messageErrors').remove();
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
                            $('form#update-proof-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {

                $.notify({
                    title: "Sửa minh chứng thành công",
                    message: ":D",
                    icon: 'fa fa-check'
                }, {
                    type: "success"
                });
                $('div#updateModal').modal('hide');
                oTable.draw();
            }
        }
    });
});

$('body').on('click', 'button#proof-destroy', function (e) {
    var id = $(this).attr("data-proof-id");
    var url = $(this).attr('data-proof-destroy-link');

    swal({
        title: "Bạn chắc chưa?",
        text: "Bạn sẽ không thể khôi phục lại dữ liệu !!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Có, tôi chắc chắn!",
        cancelButtonText: "Không, Hủy dùm tôi!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: url,
                type: 'GET',
                cache: false,
                data: {"id": id},
                success: function (data) {
                    if (data.status === true) {
                        swal("Deleted!", "Đã xóa file " + data.proof.name, "success");
                        $('.sa-confirm-button-container').click(function () {
                            oTable.draw();
                        })
                    } else {
                        swal("Cancelled", "Không tìm thấy file !!! :)", "error");
                    }
                }
            });
        } else {
            swal("Đã hủy", "Đã hủy xóa File:)", "error");
        }
    });
});

$('body').on('click', 'button#btn-upload-proof', function (e) {
    e.preventDefault();
    var formData = new FormData($('form#upload-proof-form')[0]);
    var fileUpload = document.getElementById('fileUpload');
    var inss = fileUpload.files.length;
    for (var x = 0; x < inss; x++) {
        file = fileUpload.files[x];
        formData.append("fileUpload[]", file);
    }
    var url = $(this).attr('data-link');
    $('.form-row').find('span.messageErrors').remove();
    $.ajax({
        type: "post",
        url: url,
        data: formData,
        processData: false,
        dataType: 'json',
        contentType: false,
        enctype: 'multipart/form-data',
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
                            $('form#upload-proof-form').find('.' + elementName).parents('.form-row').append('<span class="messageErrors" style="color:red">' + messageValue + '</span>');
                        });
                    });
                }
            } else if (result.status === true) {
                $.notify({
                    title: "Thêm minh chứng thành công",
                    message: ":D",
                    icon: 'fa fa-check'
                }, {
                    type: "success"
                });
                $('div#addModal').modal('hide');
                oTable.draw();
            }
        }
    });
});
//
// var x, i, j, selElmnt, a, b, c;
// /*look for any elements with the class "custom-select":*/
// x = document.getElementsByClassName("custom-select");
// for (i = 0; i < x.length; i++) {
//     selElmnt = x[i].getElementsByTagName("select")[0];
//     /*for each element, create a new DIV that will act as the selected item:*/
//     a = document.createElement("DIV");
//     a.setAttribute("class", "select-selected");
//     a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
//     x[i].appendChild(a);
//     /*for each element, create a new DIV that will contain the option list:*/
//     b = document.createElement("DIV");
//     b.setAttribute("class", "select-items select-hide");
//     for (j = 0; j < selElmnt.length; j++) {
//         /*for each option in the original select element,
//         create a new DIV that will act as an option item:*/
//         c = document.createElement("DIV");
//         c.innerHTML = selElmnt.options[j].innerHTML;
//         c.addEventListener("click", function(e) {
//             /*when an item is clicked, update the original select box,
//             and the selected item:*/
//             var y, i, k, s, h;
//             s = this.parentNode.parentNode.getElementsByTagName("select")[0];
//             h = this.parentNode.previousSibling;
//             for (i = 0; i < s.length; i++) {
//                 if (s.options[i].innerHTML == this.innerHTML) {
//                     s.selectedIndex = i;
//                     h.innerHTML = this.innerHTML;
//                     y = this.parentNode.getElementsByClassName("same-as-selected");
//                     for (k = 0; k < y.length; k++) {
//                         y[k].removeAttribute("class");
//                     }
//                     this.setAttribute("class", "same-as-selected");
//                     break;
//                 }
//             }
//             h.click();
//         });
//         b.appendChild(c);
//     }
//     x[i].appendChild(b);
//     a.addEventListener("click", function(e) {
//         /*when the select box is clicked, close any other select boxes,
//         and open/close the current select box:*/
//         e.stopPropagation();
//         closeAllSelect(this);
//         this.nextSibling.classList.toggle("select-hide");
//         this.classList.toggle("select-arrow-active");
//     });
// }
// function closeAllSelect(elmnt) {
//     /*a function that will close all select boxes in the document,
//     except the current select box:*/
//     var x, y, i, arrNo = [];
//     x = document.getElementsByClassName("select-items");
//     y = document.getElementsByClassName("select-selected");
//     for (i = 0; i < y.length; i++) {
//         if (elmnt == y[i]) {
//             arrNo.push(i)
//         } else {
//             y[i].classList.remove("select-arrow-active");
//         }
//     }
//     for (i = 0; i < x.length; i++) {
//         if (arrNo.indexOf(i)) {
//             x[i].classList.add("select-hide");
//         }
//     }
// }
// /*if the user clicks anywhere outside the select box,
// then close all select boxes:*/
// document.addEventListener("click", closeAllSelect);