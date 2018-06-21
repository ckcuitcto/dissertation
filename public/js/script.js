function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imageChange').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

<!--Khoa coppy-->

$(function () {
    $('img').bind('dragstart', function (event) {
        event.preventDefault();
    });
    $('body').delegate("img").on("contextmenu", function () {
        return false;
    });
});
$(document).keydown(function (event) {
    if (event.keyCode == 123) {
        return false;
    } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
        return false;
    }
});


if (document.layers) {
    document.captureEvents(Event.MOUSEDOWN);

    $(document).mousedown(function () {
        return false;
    });
}
else {
    $(document).mouseup(function (e) {
        if (e != null && e.type == "mouseup") {
            //Check the Mouse Button which is clicked.
            if (e.which == 2 || e.which == 3) {
                //If the Button is middle or right then disable.
                return false;
            }
        }
    });
}

//Disable the Context Menu event.
$(document).contextmenu(function () {
    return false;
});
