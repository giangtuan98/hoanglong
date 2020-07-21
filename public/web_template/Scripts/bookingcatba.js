BookingCatba = (function () {
    var bindEvents = function () {
        $("#notecaptcha").hide();
        $(".tabcb a").click(function () {
            var tickettype = $(this).attr("data-tickettype");
            var div = $(this).attr("aria-controls");
            $(".tab-pane").html("");
            loadformseachshift(tickettype, div);
        });
        if ($("#frBookingCatba1").length) {
            bindChangeDeparture();
        }
        if ($(".bookingcb .loadshiftcb").length) {
            loadShift();
        }
    };

    var GoToPayment = function () {
        var href = $(".payment-href-cb").val();
        location.href = href;
    }

    var bindChangeDeparture = function () {
        $("#frBookingCatba1 #departure").change(function () {
            var value = $(this).val();
            $.ajax({
                method: "GET",
                url: "/BookingCatBa/ChangeDeparture?departure=" + value,
                success: function (data) {
                    $('#frBookingCatba1 #destination').html(data);
                    $("#frBookingCatba1 #destination").niceSelect("update");
                    if (value === "10") {
                        $("#CbHostelModal input[name=isroundtrip]").val(0);
                        //$("#CbHostelModal").modal("show");
                    }
                },
                error: function () {
                }
            });
        });
        $("#frBookingCatbaRoundTrip #departure").change(function () {
            var value = $(this).val();
            $.ajax({
                method: "GET",
                url: "/BookingCatBa/ChangeDeparture?departure=" + value,
                success: function (data) {
                    $('#frBookingCatbaRoundTrip #destination').html(data);
                    $("#frBookingCatbaRoundTrip #destination").niceSelect("update");
                    $("#cbcombo input").prop("checked", false);
                    if (value === "10") {
                        $("#CbHostelModal input[name=isroundtrip]").val(1);
                        //$("#CbHostelModal").modal("show");
                        //$("#cbcombo").removeClass("hidden");
                    }
                    else {
                        $("#cbcombo").addClass("hidden");
                    }
                },
                error: function () {
                }
            });
        });
    }

    var usingCombo = function () {
        $("#CbHostelModal input[name=isusingcombo]").val(1);
        var isroundtrip = $("#CbHostelModal input[name=isroundtrip]").val();
        if (isroundtrip === "1") {
            $("#cbcombo input").prop("checked", true);
            $("#CbHostelModal input[name=isusingcombo]").val(0);
        } else {
            $(".tabcb a[href=#roundtrip]").click();
        }
    }

    var loadformseachshift = function (type, div) {
        $(".step1").html("");
        $(".step2").html("");
        $(".step3").html("");
        $(".seatselected").html("");
        $(".step4").html("");
        $(".step1").slideUp();
        $(".step2").slideUp();
        $(".step3").slideUp();
        $(".seatselected").slideUp();
        $(".step4").slideUp();
        App.blockUI($("body"));
        $.ajax({
            url: "/bookingcatba/LoadFormSearchShift",
            data: { tickettype: type },
            success: function (data) {
                $("#" + div).html(data);
                $(".step1").slideDown();
                $('html, body').animate({
                    scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                }, 'slow');
                App.unblockUI($("body"));
                //bindEvents();
                bindSearchShiftRoundTrip();
                bindChangeDeparture();
                bindClickSearchShift();
                Custom.Init();
                App.unblockUI($("body"));
            },
            error: function () {
                $(".step1").slideDown();
                $(".step1").html("Load fail");
                App.unblockUI($("body"));
            }
        });
    }
    var bindClickSearchShift = function () {
        $(".btnSearchShiftCB").unbind('click');
        $(".btnSearchShiftCB").click(function (e) {
            e.preventDefault();
            var culture = $(this).attr('data-culture');
            var dep = $("#departure").val();
            var des = $("#destination").val();
            var date = $("#datebook").val();
            if (dep == "") {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn điểm đi trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please choose departure before search");
                }
                return false;
            }
            if (des == "") {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn điểm đến trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please choose destination before search");
                }
                return false;
            }
            if (dep == des) {
                if (culture == "vi") {
                    Alert.Warning("Điểm đi và điểm đến không thể trùng nhau");
                } else {
                    Alert.Warning("Departure and destination can't duplicate");
                }
                return false;
            }
            if (date == "") {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn ngày đi trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please choose date before search");
                }
                return false;
            }
            loadShift();
            return false;
        });
    };
    var bindSearchShiftRoundTrip = function () {
        $(".btnSearchShiftCBRoundTrip").unbind('click');
        $(".btnSearchShiftCBRoundTrip").click(function (e) {
            e.preventDefault();
            var culture = $(this).attr('data-culture');
            var dep = $("#departure").val();
            var des = $("#destination").val();
            var startdate = $("#frBookingCatbaRoundTrip input[name=startdate]").val();
            var enddate = $("#frBookingCatbaRoundTrip input[name=enddate]").val();
            if (dep == "") {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn điểm đi trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please choose departure before search");
                }
                return false;
            }
            if (des == "") {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn điểm đến trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please choose destination before search");
                }
                return false;
            }
            if (dep == des) {
                if (culture == "vi") {
                    Alert.Warning("Điểm đi và điểm đến không thể trùng nhau");
                } else {
                    Alert.Warning("Departure and destination can't duplicate");
                }
                return false;
            }
            if (startdate == "") {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn ngày đi trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please select departure date before finding trip");
                }
                return false;
            }
            if (enddate == "") {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn ngày về trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please select return date before finding trip");
                }
                return false;
            }
            loadShiftRoundTrip();
            return false;
        });
    };
    var loadShift = function () {
        App.blockUI($("body"));
        $.ajax({
            method: 'POST',
            url: '/bookingcatba/LoadShift',
            data: $("#frBookingCatba1").serialize(),
            success: function (data) {
                console.log(data);
                if (data.success === 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".pnlresulbookingcatba").html(data);
                    jQuery('html,body').animate({
                        scrollTop: $(".pnlresulbookingcatba").offset().top - $('.lightHeader').height() //$(".pnlresulbookingcatba").position().top
                    }, 'slow');
                    bindSelectShift();
                }

                App.unblockUI($("body"));
            }, error: function () {
                $(".pnlresulbookingcatba").html("Load fail");
                App.unblockUI($("body"));
            }
        });
        return false;
    }
    var loadShiftRoundTrip = function () {
        App.blockUI($("body"));
        $.ajax({
            method: 'POST',
            url: '/bookingcatba/LoadShiftRoundTrip',
            data: $('#frBookingCatbaRoundTrip').serialize(),
            success: function (data) {
                if (data.success === 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".pnlresulbookingcatba").html(data);
                    jQuery('html,body').animate({
                        scrollTop: $(".pnlresulbookingcatba").offset().top - $('.lightHeader').height() //$(".pnlresulbookingcatba").position().top
                    }, 'slow');
                    bindSelectShiftRoundTrip();
                    bindConfirmShiftRoundTrip();
                }
                App.unblockUI($("body"));
            }, error: function () {
                $(".pnlresulbookingcatba").html("Load fail");
                App.unblockUI($("body"));
            }
        });
        return false;
    }
    var bindConfirmShiftRoundTrip = function () {
        $(".btnConfirmShiftRoundTrip").click(function () {
            var startshift = $("#frSelectShiftRoundTrip #hdstartshift").val();
            var endshift = $("#frSelectShiftRoundTrip #hdendshift").val();
            var startdate = $("#frSelectShiftRoundTrip #startdate").val();
            var enddate = $("#frSelectShiftRoundTrip #enddate").val();
            var startprice = $("#frSelectShiftRoundTrip #hdstartprice").val();
            var endprice = $("#frSelectShiftRoundTrip #hdendprice").val();
            var departure = $("#frSelectShiftRoundTrip #departure").val();
            var destination = $("#frSelectShiftRoundTrip #destination").val();
            var tickettype = $("#frSelectShiftRoundTrip #tickettype").val();
            var culture = $("#frSelectShiftRoundTrip #culture").val();
            var isCbCombo = $("#frSelectShiftRoundTrip #isCbCombo").val();
            if (startshift == '') {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn chuyến đi trước khi xác nhận thông tin.");
                } else {
                    Alert.Warning("Please select departure time before confirm.");
                }

                return false;
            }
            if (endshift == '') {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn chuyến về trước khi xác nhận thông tin.");
                } else {
                    Alert.Warning("Please select return time before confirm.");
                }
                return false;
            }
            selectShift(0, '', departure, destination, 0, tickettype, startshift, endshift, startdate, enddate, startprice, endprice, isCbCombo);
        });
    }
    var bindSelectShiftRoundTrip = function () {
        $(".startshift").click(function () {
            var shift = $(this).attr("data-id");
            var price = $(this).attr("data-price");
            $("#hdstartshift").val(shift);
            $("#hdstartprice").val(price);
            $('.startshift').removeClass('orange');
            $(this).addClass("orange");
            setTimeout(function () {
                $("#shift-" + shift).tooltip('hide');
            }, 2000);
        });
        $(".endshift").click(function () {
            var shift = $(this).attr("data-id");
            var price = $(this).attr("data-price");
            $("#hdendshift").val(shift);
            $("#hdendprice").val(price);
            $('.endshift').removeClass('orange');
            $(this).addClass("orange");
            setTimeout(function () {
                $("#shift-" + shift).tooltip('hide');
            }, 2000);
        });
        $('.shift').each(function () {
            $(this).tooltip(
                {
                    html: true,
                    title: $('#shift-' + $(this).data('id')).html()
                });
        });
    }
    var bindSelectShift = function () {
        $(".btnSelectShift").unbind('click');
        $(".btnSelectShift").click(function (e) {
            e.preventDefault();
            var shiftid = $(this).attr('data-id');
            var date = $(this).attr('data-date');
            var dep = $(this).attr('data-dep');
            var des = $(this).attr('data-des');
            var price = $(this).attr('data-price');
            var tickettype = $(this).attr('data-tickettype');
            console.log("click");
            selectShift(shiftid, date, dep, des, price, tickettype);
        });
    }
    var selectShift = function (shift, date, departure, destination, price, tickettype, startshift, endshift, startdate, enddate, startprice, endprice, isCbCombo) {
        $.ajax({
            url: '/bookingcatba/LoadFormConfirm',
            data: {
                shift: shift,
                date: date,
                departure: departure,
                destination: destination,
                price: price,
                tickettype: tickettype,
                startshift: startshift,
                endshift: endshift,
                startdate: startdate,
                enddate: enddate,
                startprice: startprice,
                endprice: endprice,
                isCbCombo
            },
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step2").html(data);
                    $(".step1").slideUp();
                    $(".step2").slideDown();
                    jQuery('html,body').animate({
                        scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                    }, 'slow');
                    //jQuery('html,body').animate({
                    //    scrollTop: $(".step2").position().top - $('.lightHeader').height()
                    //}, 'slow');
                    gotostep(2);
                    bindConfirmInfo();
                    bindGoToStep();
                    bindClickTransaction();
                }

            }
        });
    }
    var bindConfirmInfo = function () {
        $("#confirmcatba").unbind('click');
        $("#confirmcatba").click(function (e) {
            e.preventDefault();
            var culture = $(this).attr('data-culture');
            if ($("#frConfirmCatbaStep2 input[name=isValid]").val() === "False" || $("#frConfirmCatbaStep2 input[name=isValidStart]").val() === "False" || $("#frConfirmCatbaStep2 input[name=isValidEnd]").val() === "False") {
                Alert.Warning(culture == "vi" ? "Bạn đang đặt quá số lượng vé cho phép, vui lòng kiểm tra lại" : "You are booking too many tickets, please check again");
                return false;
            }
            var child = parseInt($("#childs").val());
            var audult = parseInt($("#adults").val());
            var checkFullname = $("#customername").val().trim();
            var checkPhone = $("#phone").val().trim();
            var checkEmail = $("#email").val().trim();
            if (child + audult > 10) {
                if (culture == "vi") {
                    $("#adults").focus();
                    Alert.Warning("Bạn chỉ được đặt tối đa 10 vé. Vui lòng thao tác lại.");
                } else {
                    $("#adults").focus();
                    Alert.Warning("10 tickets is maximum for 1 booked times. Please check again.");
                }
                return false;
            }
            if (checkFullname == '') {
                $("#customername").focus();
                if (culture == "vi") {
                    Alert.Warning("Xin vui lòng điền tên của bạn.");
                } else {
                    Alert.Warning("Please fill your name.");
                }
                return false;
            }
            if (checkPhone == '') {
                $("#phone").focus();
                if (culture == "vi") {
                    Alert.Warning("Xin vui lòng điền số điện thoại của bạn.");
                } else {
                    Alert.Warning("Please fill your phone.");
                }
                return false;
            }
            if (checkEmail == '') {
                $("#email").focus();
                if (culture == "vi") {
                    Alert.Warning("Xin vui lòng điền email của bạn.");
                } else {
                    Alert.Warning("please fill your email");
                }
                return false;
            }
            confirmInfo();
            return false;
        });
    }
    var confirmInfo = function () {
        App.blockUI($("body"));
        $.ajax({
            method: "POST",
            url: "/bookingcatba/loadcustomerinfo",
            data: $("#frConfirmCatbaStep2").serialize(),
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step3").html(data);
                    jQuery('html,body').animate({
                        scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                    }, 'slow');
                    gotostep(3);
                    bindConfirmTicket();
                    bindGoToStep();
                    bindClickPrivacy();
                    $("#notecaptcha").hide();
                }
                App.unblockUI($("body"));
            }, error: function () {
                Alert.Warning("Xảy ra lỗi.");
                App.unblockUI($("body"));
            }
        });
        return false;
    }
    var bindConfirmTicket = function () {
        $("#catbaSubmit").unbind('click');
        $("#catbaSubmit").click(function (e) {
            e.preventDefault();
            var captext = $("#CapImageText").val();
            var captextinput = $("#CaptchaCodeText").val();
            if (captext == captextinput) {
                if ($("#frConfirmRoundTripTicket").length) {
                    confirmRoundTripTicket();
                } else {
                    confirmTicket();
                }
            } else {
                $("#notecaptcha").show();
                $(".note").hide();
            }
        });
    };

    var confirmTicket = function () {
        App.blockUI($("body"));
        var paymenttype = $("#frConfirmTicketCb input[name='paymenttype']").val();
        $.ajax({
            url: '/bookingcatba/confirmticket',
            data: $("#frConfirmTicketCb").serialize(),
            success: function (data) {
                if (data.success === 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step4").html(data);
                    jQuery('html,body').animate({
                        scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                    }, 'slow');
                    gotostep(4);
                    if (paymenttype != 5) {
                        bindPaymentSuccess();
                    }
                    
                }
                App.unblockUI($("body"));
            }
        });
        return false;
    }
    var confirmRoundTripTicket = function () {
        App.blockUI($("body"));
        var paymenttype = $("#frConfirmRoundTripTicket input[name='paymenttype']").val();
        $.ajax({
            url: '/bookingcatba/confirmticketroundtrip',
            data: $("#frConfirmRoundTripTicket").serialize(),
            success: function (data) {
                if (data.success === 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step4").html(data);
                    jQuery('html,body').animate({
                        scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                    }, 'slow');
                    gotostep(4);
                    if (paymenttype != 5) {
                        bindPaymentSuccess();
                    }
                }
                App.unblockUI($("body"));
            }
        });
        return false;
    }

    var bindPaymentSuccess = function () {
        var culture = $("#frConfirmCatbaStep2 #hdculture").val();
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
    };
    var bindClickTransaction = function () {
        $('.transaction input[type="radio"]').each(function () {
            $(this).unbind('click');
            $(this).click(function () {
                var type = $(this).val();
                checkTransaction(type);
            });
        });
    }
    var checkTransaction = function (type) {
        var show = false;
        $('.transaction input[type="radio"]').each(function () {
            if ($(this).is(":checked")) {
                show = true;
            }
        });
        if (show) {
            $("#confirmcatba").removeAttr('disabled');
        } else {
            $("#confirmcatba").attr('disabled', 'disabled');
        }
    }
    var bindClickPrivacy = function () {
        $("#catbaprivacy").unbind('click');
        $("#catbaprivacy").click(function () {
            checkPrivacy();
        });
    };
    function checkPrivacy() {
        if ($("#catbaprivacy").is(":checked")) {
            $("#catbaSubmit").removeAttr('disabled');
        } else {
            $("#catbaSubmit").attr('disabled', 'disabled');
        }
    }
    var bindGoToStep = function () {
        $(".btngotostep").unbind('click');
        $(".btngotostep").click(function (e) {
            e.preventDefault();
            var step = $(this).attr('data-step');
            gotostep(step);
            return false;
        });
    }
    var gotostep = function (step) {
        jQuery('html,body').animate({
            scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
        }, 'slow');
        if (step == 1) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("active");
            $(".progress-step-2").addClass("disabled");
            $(".progress-step-3").addClass("disabled");
            $(".progress-step-4").addClass("disabled");
            $(".step1").slideDown();
            $(".step2").slideUp();
            $(".step3").slideUp();
            $(".step4").slideUp();
        } else if (step == 2) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("active");
            $(".progress-step-3").addClass("disabled");
            $(".progress-step-4").addClass("disabled");
            $(".step1").slideUp();
            $(".step2").slideDown();
            $(".step3").slideUp();
            $(".step4").slideUp();
        } else if (step == 3) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("complete");
            $(".progress-step-3").addClass("active");
            $(".progress-step-4").addClass("disabled");
            $(".step1").slideUp();
            $(".step2").slideUp();
            $(".step3").slideDown();
            $(".step4").slideUp();
        } else if (step == 4) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("complete");
            $(".progress-step-3").addClass("complete");
            $(".progress-step-4").addClass("active");
            $(".step1").slideUp();
            $(".step2").slideUp();
            $(".step3").slideUp();
            $(".step4").slideDown();
        } else if (step == 5) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("complete");
            $(".progress-step-3").addClass("complete");
            $(".progress-step-4").addClass("complete");
            $(".step1").slideUp();
            $(".step2").slideUp();
            $(".step3").slideUp();
            $(".step4").slideDown();
        }
    };

    var cancelCbTicket = function (id) {
        App.blockUI();
        var culture = $("#frConfirmCatbaStep2 #hdculture").val();
        $.ajax({
            url: "/BookingCatBa/CancelCbTicket",
            data: { id },
            success: function () {
                if (culture === "vi") {
                    Alert.Warning("Vé của bạn đã bị hủy.Vui lòng quay lại sau.");
                } else {
                    Alert.Warning("Your ticket was cancelled. Please come back later.");
                }
                $(".pnlresulbookingcatba").html("");
                $(".step4").slideUp();
                $(".step3").slideUp();
                $(".step2").slideUp();
                $(".step1").slideDown();
                $(".pnlresulbookingcatba").slideUp();
                App.unblockUI();
            },
            error: function () {
                if (culture === "vi") {
                    Alert.Warning("Vé của bạn đã bị hủy.Vui lòng quay lại sau.");
                } else {
                    Alert.Warning("Your ticket was cancelled. Please come back later.");
                }
                $(".pnlresulbookingcatba:").html("");
                $(".pnlresulbookingcatba").slideUp();
                $(".step4").slideUp();
                App.unblockUI();
            }
        });
        App.unblockUI();
    }

    var checkSeatLimousine = function (date, shift, numberTicket, type) {
        var hostelValue = $("#frConfirmCatbaStep2 select[name=hostel]").val();
        $("#frConfirmCatbaStep2 select[name=hostel]").html("");
        for (var i = 1; i <= numberTicket; i++) {
            $("#frConfirmCatbaStep2 select[name=hostel]").append("<option value='" + i + "'>" + i + "</option>");
        }
        $("#frConfirmCatbaStep2 select[name=hostel]").val(hostelValue);
        App.blockUI();
        $.ajax({
            url: "/BookingCatBa/CheckSeatLimousine",
            data: { date, shift, numberTicket },
            success: function (result) {
                switch (type) {
                    case 1:
                        if (result.success) {
                            if (result.isEnough) {
                                $("#frConfirmCatbaStep2 input[name=isValidStart]").val("True");
                                $("#warningLimousineSeatsStart").addClass("hidden");
                            } else {
                                $("#frConfirmCatbaStep2 input[name=isValidStart]").val("False");
                                $("#warningLimousineSeatsStart").removeClass("hidden");
                                var culture = $("#frConfirmCatbaStep2").attr("data-culture");
                                var text = culture == "vi"
                                    ? "Chỉ còn " + result.freeSeat + " chỗ trống chiều đi"
                                    : "Only " + result.freeSeat + " seats left in departure shift";
                                $("#warningLimousineSeatsStart").text(text);
                                Alert.Error(text);
                            }
                        } else {
                            $("#frConfirmCatbaStep2 input[name=isValid]").val("False");
                            $("#warningLimousineSeatsStart").removeClass("hidden");
                            Alert.Error(result.msg);
                        }
                        break;
                    case 2:
                        if (result.success) {
                            if (result.isEnough) {
                                $("#frConfirmCatbaStep2 input[name=isValidEnd]").val("True");
                                $("#warningLimousineSeatsEnd").addClass("hidden");
                            } else {
                                $("#frConfirmCatbaStep2 input[name=isValidEnd]").val("False");
                                $("#warningLimousineSeatsEnd").removeClass("hidden");
                                var culture = $("#frConfirmCatbaStep2").attr("data-culture");
                                var text = culture == "vi"
                                    ? "Chỉ còn " + result.freeSeat + " chỗ trống chiều về"
                                    : "Only " + result.freeSeat + " seats leftin destination shift";
                                $("#warningLimousineSeatsEnd").text(text);
                                Alert.Error(text);
                            }
                        } else {
                            $("#frConfirmCatbaStep2 input[name=isValid]").val("False");
                            $("#warningLimousineSeats").removeClass("hidden");
                            Alert.Error(result.msg);
                        }
                        break;
                    default:
                        if (result.success) {
                            if (result.isEnough) {
                                $("#frConfirmCatbaStep2 input[name=isValid]").val("True");
                                $("#warningLimousineSeats").addClass("hidden");
                            } else {
                                $("#frConfirmCatbaStep2 input[name=isValid]").val("False");
                                $("#warningLimousineSeats").removeClass("hidden");
                                var culture = $("#frConfirmCatbaStep2").attr("data-culture");
                                var text = culture == "vi"
                                    ? "Chỉ còn " + result.freeSeat + " chỗ trống"
                                    : "Only " + result.freeSeat + " seats left";
                                $("#warningLimousineSeats").text(text);
                                Alert.Error(text);
                            }
                        } else {
                            $("#frConfirmCatbaStep2 input[name=isValid]").val("False");
                            $("#warningLimousineSeats").removeClass("hidden");
                            Alert.Error(result.msg);
                        }
                        break;
                }
            }
        });
        App.unblockUI();
    }

    var init = function () {
        bindEvents();
        bindClickSearchShift();
    };
    return {
        Init: init,
        LoadShift: loadShift,
        CancelCbTicket: cancelCbTicket,
        CheckSeatLimousine: checkSeatLimousine,
        UsingCombo: usingCombo,
        GoToPayment: GoToPayment
    };
})();


