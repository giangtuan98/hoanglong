Library = (function () {
    var bindEvents = function () {
        $(".otherimages").click(function () {
            var id = $(this).attr("data-id");
            App.blockUI($("#images"));
            $.ajax({
                url: '/library/LoadImage',
                data: { id: id },
                success: function (data) {
                    $("#images").html(data);
                    App.unblockUI($("#images"));
                    jQuery('html,body').animate({
                        scrollTop: $("#images").position().top
                    }, 'slow');
                    bindEvents();
                }, error: function () {
                    App.unblockUI($("#images"));
                }
            });
            return false;
        });
        $(".othervideos").click(function () {
            var id = $(this).attr("data-id");
            App.blockUI($("#video"));
            $.ajax({
                url: '/library/LoadVideo',
                data: { id: id },
                success: function (data) {
                    $("#video").html(data);
                    App.unblockUI($("#video"));
                    jQuery('html,body').animate({
                        scrollTop: $("#video").position().top
                    }, 'slow');
                    bindEvents();
                }, error: function () {
                    App.unblockUI($("#video"));
                }
            });
            return false;
        });
    };
    var init = function () {
        bindEvents();
    };
    return {
        Init: init
    };
})();