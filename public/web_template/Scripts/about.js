About = (function() {
    var bindEvents = function() {
        $(".about-content").click(function () {
            var url = $(this).attr('data-link');
            App.blockUI($(".aboutcontent"));
            $.ajax({
                url: url,
                success: function (data) {
                    $(".aboutcontent").html(data);
                    App.unblockUI($(".aboutcontent"));
                    $('html,body').animate({
                        scrollTop: $(".aboutcontent").position().top
                    }, 'slow');
                },error:function() {
                    $(".aboutcontent").html("Lỗi");
                    App.unblockUI($(".aboutcontent"));
                }
            });
        });
    };
    var init = function() {
        bindEvents();
    };
    return {
        Init:init
    }
})();