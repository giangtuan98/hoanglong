Contact = (function () {
    var bindEvents = function () {
        if ($("#frLoadOfficeByCity").length) {
            var stop = $("#frLoadOfficeByCity").attr('data-stop');
            if (stop != undefined && stop > 0) {
                loadOfficeByCity(stop);
            }
            var stop2 = $("#frLoadOfficeByCity #City option:selected").val();
            loadOfficeByCity(stop2);
            $("#frLoadOfficeByCity input[type=button]").click(function () {
                var stop3 = $("#frLoadOfficeByCity #City option:selected").val();
                loadOfficeByCity(stop3);
            });
        }
        $("#frSendMessage").submit(function () {
            var culture = $("#hdculture").val();
            var name = $("#name").val();
            var email = $("#email").val();
            var message = $("#message").val();
            if (name == '' || name.trim() == '') {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng nhập tên trước khi gửi tin nhắn.");
                } else {
                    Alert.Warning("Please fill your name before send message.");
                }
                $("#name").focus();
                return false;
            }
            if (email == '' || email.trim() == '') {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng nhập email trước khi gửi tin nhắn.");
                } else {
                    Alert.Warning("Please fill your email before send message.");
                }
                $("#email").focus();
                return false;
            }
            if (message == '' || message.trim() == '') {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng nhập tin nhắn trước khi gửi tin nhắn.");
                } else {
                    Alert.Warning("Please fill your message before send message.");
                }
                $("#message").focus();
                return false;
            }
            App.blockUI($("body"));
            $.ajax({
                url: '/contact/sendmessage',
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    if (data.success == 1) {
                        $("#frSendMessage").trigger('reset');
                        Alert.Success(data.message);
                    } else {
                        Alert.Warning(data.message);
                    }
                    App.unblockUI($("body"));
                    grecaptcha.reset();
                }, error: function () {
                    Alert.Warning("Lỗi");
                    App.unblockUI($("body"));
                }
            });
            return false;
        });
    };
    var loadOfficeByCity = function (id) {
        App.blockUI($(".listoffice"));
        $.ajax({
            url: '/contact/LoadOfficeByCity?id=' + id,
            success: function (data) {
                $(".listoffice").html(data);
                jQuery('html,body').animate({
                    scrollTop: $(".listoffice").position().top
                }, 'slow');
                App.unblockUI($(".listoffice"));
            }, error: function () {
                $(".listoffice").html("Load fail");
                App.unblockUI($(".listoffice"));
            }
        });
    }
    var init = function () {
        bindEvents();
    };
    return {
        Init: init,
        LoadOfficeByCity: loadOfficeByCity
    }
})();