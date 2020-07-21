Home = (function () {
    var bindEnvents = function () {
        if ($(".searchTour #departure").length) {
            $(".searchTour #departure").click(function () {
                jQuery('html,body').animate({
                    scrollTop: $(".searchTour #departure").position().top - 70
                }, 'slow');
            });
        }
        $(".icon-vn").click(function () {
            var returnurl = $(this).attr("data-returnurl");
            window.location = "/changelanguage/changeLanguage?returnurl=" + returnurl + "&culture=vi";
        });
        $(".icon-uk").click(function () {
            var returnurl = $(this).attr("data-returnurl");
            window.location = "/changelanguage/changeLanguage?returnurl=" + returnurl + "&culture=en";
        });
    };
    var init = function () {
        bindEnvents();
        Booking.BindClickSearchShift();
    };
    return {
        Init: init
    };
})();