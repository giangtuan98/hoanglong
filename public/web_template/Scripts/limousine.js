Limousine = (function () {

    var gotostep = function (step) {
        if (step === 1) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("active");
            $(".progress-step-2").addClass("disabled");
            $(".progress-step-3").addClass("disabled");
            $(".progress-step-4").addClass("disabled");
        } else if (step === 2) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("active");
            $(".progress-step-3").addClass("disabled");
            $(".progress-step-4").addClass("disabled");
        } else if (step === 3) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("complete");
            $(".progress-step-3").addClass("active");
            $(".progress-step-4").addClass("disabled");
        } else if (step === 4) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("complete");
            $(".progress-step-3").addClass("complete");
            $(".progress-step-4").addClass("active");
        } else if (step === 5) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("complete");
            $(".progress-step-3").addClass("complete");
            $(".progress-step-4").addClass("complete");
        }
    }

    var checkTransaction = function () {
        var show = false;
        $('.transaction input[type="radio"]').each(function () {
            if ($(this).is(":checked")) {
                show = true;
            }
        });
        if (show) {
            $("#privacy").removeAttr("disabled");
        } else {
            $("#privacy").attr("disabled", "disabled");
        }
    };

    var bindClickTransaction = function () {
        $('.transaction input[type="radio"]').each(function () {
            $(this).unbind("click");
            $(this).click(function () {
                checkTransaction();
            });
        });
    }

    var checkPrivacy = function () {
        if ($("#privacy").is(":checked")) {
            $("#btnConfirmTicket").removeAttr("disabled");
        } else {
            $("#btnConfirmTicket").attr("disabled", "disabled");
        }
    }

    var bindClickPrivacy = function () {
        $("#privacy").unbind("click");
        $("#privacy").click(function () {
            checkPrivacy();
        });
    };

    var checkBeforePayment = function (ticketid, transid, link) {
        App.blockUI();
        $.ajax({
            url: "/shortshift/UpdateAllWaitingPayment",
            data: { listordernumber: ticketid, transactionid: transid },
            success: function () {
                window.location = link;
                App.unblockUI();
            }
        });
    }

    var bindClickPayment = function () {
        $(".btnPayment").unbind("click");
        $(".btnPayment").click(function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var transid = $(this).attr("data-transid");
            var link = $(this).attr("data-link");
            checkBeforePayment(id, transid, link);
        });
    };

    var cancelTicket = function (ticketid, culture, transid) {
        App.blockUI();
        $.ajax({
            url: "/shortshift/CancelAllTicket",
            data: { listordernumber: ticketid, transactionid: transid },
            success: function () {
                if (culture === "vi") {
                    Alert.Warning("Vé của bạn đã bị hủy.Vui lòng quay lại sau.");
                } else {
                    Alert.Warning("Your ticket was cancelled. Please come back later.");
                }
                $(".resultlimousine").html("");
                $(".step4").slideUp();
                $(".step3").slideUp();
                $(".step2").slideUp();
                $(".step1").slideDown();
                $(".resultlimousine").slideUp();
                App.unblockUI();
            },
            error: function () {
                if (culture === "vi") {
                    Alert.Warning("Vé của bạn đã bị hủy.Vui lòng quay lại sau.");
                } else {
                    Alert.Warning("Your ticket was cancelled. Please come back later.");
                }
                $(".resultlimousine").html("");
                $(".step4").slideUp();
                App.unblockUI();
            }
        });
    }

    var bindClickCancelTicket = function () {
        $(".btnCancelTicket").unbind("click");
        $(".btnCancelTicket").click(function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var transid = $(this).attr("data-transid");
            var culture = $(this).attr("data-culture");
            cancelTicket(id, culture, transid);
        });
    };

    var bindPaymentSuccess = function (culture) {
        if (culture === "vi") {
            Alert.Message("Bạn có 3 phút để chuyển vào trang thanh toán và 15 phút trong trang thanh toán để thực hiện giao dịch. Quá thời gian trên vé của bạn sẽ bị hủy.");
        }
        else {
            Alert.Message("You have 3 minutes to click on Online Payment button and 15 minutes for payment. Over time, that transaction will be cancelled.");
        }
        $(function () {
            $(".timer").countdown({
                image: "/scripts/plugin/countdowntimer/digits.png",
                format: "mm:ss",
                startTime: "03:00",
                timerEnd: function () {
                    $(".btnCancelTicket").click();
                }
            });
        });
        bindClickPayment();
        bindClickCancelTicket();
    };

    var confirmTicket = function () {
        App.blockUI();
        $.ajax({
            method: "POST",
            url: "/Limousine/ConfirmTicket",
            data: $("#frConfirmTicket").serialize(),
            success: function (data) {
                if (data.success !== undefined && data.success === 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step2").slideUp();
                    $(".step1").slideUp();
                    $(".seatselected").slideUp();
                    $(".step3").slideUp();
                    $(".resultlimousine").slideUp();
                    $(".step4").html(data);
                    $(".step4").slideDown();
                    gotostep(4);
                    if ($(".mainContentSection > .container").hasClass("maintb")) {
                        $("html, body").animate({
                            scrollTop: $(".mainContentSection > .container").position().top
                        }, "slow");
                    } else {
                        $("html,body").animate({
                            scrollTop: $(".tab-content").offset().top - $(".lightHeader").height()
                        }, "slow");
                    }

                    var culture = $("#culture").val();
                    bindPaymentSuccess(culture);
                }
                App.unblockUI();
            }, error: function () {
                $(".step4").html("Load fail");
                App.unblockUI();
            }
        });
    }

    var bindConfirmTicket = function () {
        $("#btnConfirmTicket").unbind("click");
        $("#btnConfirmTicket").click(function (e) {
            e.preventDefault();
            var captext = $("#CapImageText").val();
            var captextinput = $("#CaptchaCodeText").val();
            if (captext === captextinput) {
                confirmTicket();
            } else {
                $("#CaptchaCodeText").focus();
                $("#notecaptcha").show();
                $(".note").hide();
            }
        });
    };

    var bindSelectLimousineSeat = function () {
        $(".limousineitems .item-click").click(function () {
            if (!$(this).hasClass("disable-click")) {
                if ($(this).hasClass("active"))
                    $(this).removeClass("active");
                else
                    $(this).addClass("active");
            };
        });

        $("#frBookingLimousine").submit(function (e) {
            e.preventDefault();
            if ($("#frBookingLimousine input[name=name]").val() === "") {
                Alert.Warning("Vui lòng nhập tên!");
                return;
            }
            if ($("#frBookingLimousine input[name=phone]").val().length !== 10 && $("#frBookingLimousine input[name=phone]").val().length !== 11) {
                Alert.Warning("Số điện thoại phải là số gồm 10 và 11 ký tự!");
                return;
            }
            if ($(".limousineitems .limousineitem.active").length === 0) {
                Alert.Warning("Vui lòng chọn ghế.");
                return;
            }
            $("#frBookingLimousine input[name=seats]").val("");
            $(".limousineitems .limousineitem.active").each(function () {
                $("#frBookingLimousine input[name=seats]").val($("#frBookingLimousine input[name=seats]").val() + "," + $(this).html());
            });
            $(".resultlimousine").html("");
            $(".resultlimousine").slideUp();
            $(".step2").html("");
            $(".step2").slideUp();
            $(".step3").html("");
            $(".step3").slideDown();
            $(".step4").html("");
            $(".step4").slideUp();
            App.blockUI($("body"));
            $.ajax({
                type: "POST",
                url: "/Limousine/LoadFormConfirmBooking",
                data: $(this).serialize(),
                success: function (data) {
                    $(".step3").html(data);
                    $("html,body").animate({
                        scrollTop: $(".positiontop").offset().top - $(".lightHeader").height()
                    }, "slow");
                    bindClickTransaction();
                    bindClickPrivacy();
                    bindConfirmTicket();
                    $("#notecaptcha").hide();
                    gotostep(3);
                    App.unblockUI($("body"));
                },
                error: function() {
                    alert("Error!");
                    App.unblockUI($("body"));
                }
            });
        });
    }

    var selectLimousineShift = function (shiftid, date, slot) {
        if (slot > 0) {
            $(".blockshift").removeClass("dsgnmoo");
            $(".blockshift" + shiftid).addClass("dsgnmoo");
            $(".step2").html("");
            $(".step2").slideDown();
            $(".step3").html("");
            $(".step3").slideUp();
            $(".step4").html("");
            $(".step4").slideUp();
            App.blockUI($("body"));
            $.ajax({
                type: "POST",
                url: "/Limousine/LoadSeats",
                data: { shiftid, date },
                success: function (data) {
                    if (data.msg !== undefined) {
                        Alert.Warning(data.msg);
                    } else {
                        $(".step2").html(data);
                        $("html,body").animate({
                            scrollTop: $(".positiontop").offset().top - $(".lightHeader").height()
                        }, "slow");
                        bindSelectLimousineSeat();
                        gotostep(2);
                    }
                    App.unblockUI($("body"));
                },
                error: function () {
                    $(".step2").html("Load fail");
                    App.unblockUI($("body"));
                }
            });
        } else {
            Alert.Warning("Chuyến này đã hết ghế. Vui lòng chọn lại!");
        }
    }

    var bindSelectLimousineShift = function () {
        $(".shortshift").click(function () {
            if (!$(this).hasClass("shiftdisabled")) {
                var id = $(this).attr("data-id");
                var date = $(this).attr("data-date");
                var slot = $(this).attr("data-availableslot");
                selectLimousineShift(id, date, slot);
            }
        });
    }

    var init = function () {
        $("#frSearchLimousineShift").submit(function (e) {
            e.preventDefault();
            $(".step2").html("");
            $(".step3").html("");
            $(".step4").html("");
            $(".seatselected").html("");
            $(".step2").slideUp();
            $(".step3").slideUp();
            $(".step4").slideUp();
            $(".seatselected").slideUp();
            $(".resultlimousine").slideUp();
            App.blockUI($("body"));
            $.ajax({
                type: "POST",
                url: "/Limousine/LoadShifts",
                data: $(this).serialize(),
                success: function (data) {
                    $(".resultlimousine").html(data);
                    $(".resultlimousine").slideDown();
                    $("html,body").animate({
                        scrollTop: $(".resultlimousine").offset().top - $(".lightHeader").height()
                    }, "slow");
                    bindSelectLimousineShift();
                    App.unblockUI($("body"));
                },
                error: function () {
                    $(".resultlimousine").html("Load fail");
                    $(".resultlimousine").slideDown();
                    App.unblockUI($("body"));
                }
            });
        });
    }

    return {
        Init: init
    }
})();