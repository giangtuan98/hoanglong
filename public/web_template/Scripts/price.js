Price = (function () {
    var bindEvents = function () {
        $("#frLoadPriceByCity").submit(function () {
            App.blockUI($(".listprice"));
            $.ajax({
                url: '/price/LoadListPriceByCity',
                data: $("#frLoadPriceByCity").serialize(),
                success: function (data) {
                    if (data.success == 0) {
                        Alert.Warning(data.message);
                    } else {
                        $(".listprice").html(data);
                        jQuery('html,body').animate({
                            scrollTop: $(".listprice").position().top
                        }, 'slow');
                    }
                    App.unblockUI($(".listprice"));
                }, error: function () {
                    $(".listprice").html("Load fail!");
                    App.unblockUI($(".listprice"));
                }
            });
            return false;
        });
        if ($("#frLoadPriceByCity").length) {
            $("#frLoadPriceByCity").submit();
        }
        $(".btnprice").click(function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $(".btnprice").removeClass('selected');
            $(this).addClass('selected');
            App.blockUI($(".listprice"));
            $.ajax({
                url: '/price/LoadListPriceByCity?id=' + id,
                success: function (data) {
                    $(".listprice").html(data);
                    jQuery('html,body').animate({
                        scrollTop: $(".listprice").position().top
                    }, 'slow');
                    App.unblockUI($(".listprice"));
                }, error: function () {
                    $(".listprice").html("Load fail!");
                    App.unblockUI($(".listprice"));
                }
            });
        });
    };
    var init = function () {
        bindEvents();
    };
    return {
        Init: init
    }
})();

