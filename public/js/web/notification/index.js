$('body').on('click', 'button.view-notification', function (e) {
    var url = $(this).attr('link-view');
    var id = $(this).attr('data-id');
    $.ajax({
        type: "get",
        url: url,
        data: {id: id},
        dataType: 'json',
        success: function (result) {
            if (result.status === true) {
                if (result.notification !== undefined) {
                    $.each(result.notification, function (elementName, value) {
                        $("div#myModal").find('p.' + elementName).append(value);
                    });
                    oTable.draw();
                }
            }
        }
    });
    $('#myModal').modal('show');
});

$('div#myModal').on('hidden.bs.modal', function (e) {
    $('div#myModal').find("p").html('');
});