$('body').on('click', 'button.news-destroy', function (e) {
    var id = $(this).attr("data-news-id");
    var url = $(this).attr('data-news-link');
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
                        swal("Deleted!", "Đã xóa tin tức !", "success");
                        $('.sa-confirm-button-container').click(function () {
                            $.notify({
                                title: "Xóa tin tức thành công",
                                message: ":D",
                                icon: 'fa fa-check'
                            },{
                                type: "success"
                            });
                            oTable.draw();
                        })
                    } else {
                        swal("Cancelled", "Không tìm thấy tin tức !!! :)", "error");
                    }
                }
            });
        } else {
            swal("Đã hủy", "Đã hủy xóa:)", "error");
        }
    });
});