Service = (function() {
    var bindEvents = function() {
        $("#xeghengoi").slideUp();
        $(".btnXegiuongnam").click(function () {
            $(".btnCoachesType").removeClass("buttonCustomPrimary");
            $(".btnCoachesType").removeClass("buttonTransparent");
            $(".btnXegiuongnam").addClass("buttonCustomPrimary");
            $(".btnXeghengoi").addClass("buttonTransparent");
            $("#xegiuongnam").slideDown();
            $("#xeghengoi").slideUp();
        });
        $(".btnXeghengoi").click(function () {
            $(".btnCoachesType").removeClass("buttonCustomPrimary");
            $(".btnCoachesType").removeClass("buttonTransparent");
            $(".btnXeghengoi").addClass("buttonCustomPrimary");
            $(".btnXegiuongnam").addClass("buttonTransparent");
            $("#xegiuongnam").slideUp();
            $("#xeghengoi").slideDown();
        });
    };
    var init = function() {
        bindEvents();
    };
    return {
        Init: init
    };
})();