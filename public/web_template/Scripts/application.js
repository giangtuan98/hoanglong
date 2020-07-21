/* --------------------------------------------------------------------------
	=onload
--------------------------------------------------------------------------- */
$(function () {
    // Modules bootstrap
    Core.Init();
    Custom.Init();
    Home.Init();
    Library.Init();
    ShortShift.Init();
    Contact.Init();
    Service.Init();
    Booking.Init();
    BookingCatba.Init();
    About.Init();
    Price.Init();
    Limousine.Init();
});
$(document).ready(function () {
    $('#tababout  .ui-collapsible-heading-toggle').click(function (e) {
        e.preventDefault();
        var pos = $(this).parent().parent().attr('data-position');
        var h = $(this).height() * parseInt(pos);
        var add = pos * 16;
        setTimeout(function () {

            $("body, html").animate
                (
                {
                    scrollTop: h + add
                },
                500);

        }, 1);
    });
});
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function loadVideo(id) {
    $.ajax({
        url: '/library/LoadVideo?id=' + id,
        success: function (data) {
            $("#video").addClass("isLoaded");
            $("#video").html(data);
        },
        error: function () {
            $("#video").html("Load fail");
        }
    });
}
function backtotop() {
    jQuery('html,body').animate({
        scrollTop: 0
    }, 'slow');
}
function scrollToDiv($div) {
    // alert($($div).position().top);
    jQuery('html,body').animate({
        scrollTop: $($div).position().top
    }, 'slow');
}
function showPopup(content) {
    $("#popupError").html("<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right'>Close</a><p>" + content + "</p>");
    $("#popupError").popup("open");
}
function ClearSession() {
    $.ajax({
        url: '/booking/clearsession'
    });
}
