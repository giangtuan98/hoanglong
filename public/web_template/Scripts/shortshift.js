ShortShift = (function () {
    var bindEvents = function () {
        $(".tabshortshift a").click(function () {
            var tickettype = $(this).attr("data-tickettype");
            var div = $(this).attr("aria-controls");
            var culture = $(this).attr("data-culture");
            $(".tab-pane").html("");
            loadformseachticket(tickettype, div, culture);
        });
        $(function () {
            $(document).on('shown.bs.tooltip', function (e) {
                setTimeout(function () {
                    $(e.target).tooltip('hide');
                }, 2000);
            });
        });

    };
    var loadformseachticket = function (type, div) {
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
            url: "/shortshift/LoadFormSearchTicket",
            data: { tickettype: type },
            success: function (data) {
                $("#" + div).html(data);
                $(".step1").slideDown();
                $('html,body').animate({
                    scrollTop: $(".step1").offset().top - $('.lightHeader').height()
                }, 'slow');
                App.unblockUI($("body"));
                //bindEvents();
                bindSearchRoundTrip();
                bindSearchShortShift();
                Custom.Init();
            },
            error: function () {
                $(".step1").slideDown();
                $(".step1").html("Load fail");
                App.unblockUI($("body"));
            }
        });
    }
    var bindSearchShortShift = function () {
        $(".btnSearchShortShift").unbind('click');
        $(".btnSearchShortShift").click(function (e) {
            e.preventDefault();
            var culture = $(this).attr("data-culture");
            var dep = $("#departure").val();
            var des = $("#destination").val();
            if (dep === des) {
                if (culture === "vi") {
                    Alert.Warning("Điểm đi và điểm đến không thể trùng nhau");
                } else {
                    Alert.Warning("Departure and destination can't duplicate");
                }
            } else {
                searchShortShift();
            }
        });
    }
    var bindSearchRoundTrip = function () {
        $(".btnSearchShiftRoundTrip").unbind('click');
        $(".btnSearchShiftRoundTrip").click(function (e) {
            e.preventDefault();
            var culture = $(this).attr("data-culture");
            var dep = $("#departure").val();
            var des = $("#destination").val();
            if (dep === des) {
                if (culture === "vi") {
                    Alert.Warning("Điểm đi và điểm đến không thể trùng nhau");
                } else {
                    Alert.Warning("Departure and destination can't duplicate");
                }
            } else {
                searchShiftRoundTrip();
            }
        });
    }
    var bindLoadShiftRoundTrip = function () {
        $(".startshift").click(function () {
            var shift = $(this).attr("data-id");
            var date = $("#startdate").val();
            loadstationbyshift(shift, $("#StartStation"), date);
            $("#hdstartshift").val(shift);
            $('.startshift').removeClass('orange');
            $(this).addClass("orange");
            setTimeout(function () {
                $("#shift-" + shift).tooltip('hide');
            }, 2000);
        });
        $(".endshift").click(function () {
            var shift = $(this).attr("data-id");
            var date = $("#enddate").val();
            loadstationbyshift(shift, $("#EndStation"), date);
            $("#hdendshift").val(shift);
            $('.endshift').removeClass("orange");
            $(this).addClass("orange");
            setTimeout(function () {
                $("#shift-" + shift).tooltip("hide");
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
    var searchShortShift = function () {
        $(".step2").html("");
        $(".step3").html("");
        $(".step4").html("");
        $(".seatselected").html("");
        $(".step2").slideUp();
        $(".step3").slideUp();
        $(".step4").slideUp();
        $(".seatselected").slideUp();
        $(".pnlresulshortshift").slideUp();
        App.blockUI($("body"));
        $.ajax({
            method: "POST",
            url: "/shortshift/LoadShift",
            data: $("#frSearchShift").serialize(),
            success: function (data) {
                if (data.success === 0) {
                    Alert.Warning(data.message);
                }
                else {
                    $('.pnlresulshortshift').html(data);
                    $(".pnlresulshortshift").slideDown();
                    $('html, body').animate({
                        scrollTop: $(".pnlresulshortshift").offset().top - $('.lightHeader').height()
                    }, 'slow');
                    bindSelectShift();
                }
                App.unblockUI($("body"));
            },
            error: function () {
                $(".pnlresulshortshift").html("Load fail");
                $(".pnlresulshortshift").slideDown();
                App.unblockUI($("body"));
            }
        });
        return false;
    };
    var searchShiftRoundTrip = function () {
        $(".step2").html("");
        $(".step3").html("");
        $(".step4").html("");
        $(".seatselected").html("");
        $(".step2").slideUp();
        $(".step3").slideUp();
        $(".step4").slideUp();
        $(".seatselected").slideUp();
        $('.pnlresulshortshift').slideUp();
        App.blockUI($('body'));
        $.ajax({
            method: 'POST',
            url: '/shortshift/LoadShiftRoundTrip',
            data: $("#frSearchRoundTrip").serialize(),
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    $('.pnlresulshortshift').html(data);
                    $('.pnlresulshortshift').slideDown();
                    $('html,body').animate({
                        scrollTop: $(".pnlresulshortshift").offset().top - $('.lightHeader').height()
                    }, 'slow');
                    bindLoadShiftRoundTrip();
                    bindSelectShiftRoundTrip();
                    bindSelectShiftRoundTripSaving();
                }
                App.unblockUI($('body'));
            }, error: function () {
                $('.pnlresulshortshift').html("Load fail");
                $('.pnlresulshortshift').slideDown();
                App.unblockUI($('body'));
            }
        });
        return false;
    };
    var bindSelectShift = function () {
        $(".shortshift").click(function () {
            if (!$(this).hasClass("shiftdisabled")) {
                var id = $(this).attr("data-id");
                var date = $(this).attr("data-date");
                var slot = $(this).attr("data-availableslot");
                var routetype = $(this).attr("data-routetype");
                var dep = $(this).attr("data-departure");
                var des = $(this).attr("data-destination");
                selectShift(id, date, slot, routetype, dep, des);
            }
        });
    }
    var bindSelectShiftRoundTripSaving = function () {
        $(".shortshift").click(function () {
            var id = $(this).attr("data-id");
            var sdate = $(this).attr("data-sdate");
            var edate = $(this).attr("data-edate");
            var slot = $(this).attr("data-availableslot");
            var tickettype = $(this).attr("data-tickettype");
            selectShiftRoundTripSaving(id, sdate, edate, slot, tickettype);
        });
    }
    var bindSelectSeat = function () {
        $('.seat').click(function () {
            var culture = $("#culture").val();
            //$('.seat').removeClass('selected');
            $(".note").removeClass('hidden');

            if ($(this).hasClass('sselected')) {
                $(this).removeClass('sselected');
            } else {
                var countSeatSelected = $('.sselected').length;
                if (countSeatSelected < 3) {
                    $(this).addClass('sselected');
                }
            }
            $("#seatselect").html("");
            $("#seat").val("");
            var countBasic = 0;
            $('.seat').each(function () {
                if ($(this).hasClass('sselected')) {
                    var idseat = $(this).attr('data-id');
                    var price = $(this).attr('data-price');
                    if (idseat == 0) {
                        countBasic++;
                        //$("#seatselect").html($("#seatselect").html() + "," + countBasic + " Ghế thường");
                    } else {
                        $("#seatselect").append("VIP" + idseat + ",");
                    }

                    //$("#seat").val(idseat + ",");
                    $("#seat").val($("#seat").val() + idseat + "_" + price + ",");
                }
            });
            $("#seatselect").html($("#seatselect").html().slice(0, -1) + '');
            $("#seat").val($("#seat").val().slice(0, -1) + '');
            if (countBasic > 0) {
                if (culture === "vi") {
                    if ($("#seatselect").html() !== '') {
                        $("#seatselect").append(" và " + countBasic + " ghế thường");
                    } else {
                        $("#seatselect").append(countBasic + " ghế thường");
                    }
                } else {
                    if ($("#seatselect").html() !== '') {
                        $("#seatselect")
                            .append(" and " + countBasic + " basic" + (countBasic > 1 ? " seats" : " seat"));
                    } else {
                        $("#seatselect").append(countBasic + " basic" + (countBasic > 1 ? " seats" : " seat"));
                    }
                }
            }
        });
        return false;
    }
    var bindConfirmSeat = function () {
        $(".btnConfirmSeat").unbind('click');
        $(".btnConfirmSeat").click(function (e) {
            e.preventDefault();
            var seat = $("#seat").val();
            var culture = $("#culture").val();

            if (seat === "") {
                if (culture === "vi") {
                    Alert.Warning("Quý khách vui lòng chọn ghế trước khi đặt vé.");
                } else {
                    Alert.Warning("Please choose seat before booking ticket");
                }
            } else if ($("#Station").val() === 0) {
                if (culture === "vi") {
                    Alert.Warning("Quý khách chưa chọn địa điểm lên xe tại " + $("#departurename").val());
                } else {
                    Alert.Warning("Choose the place you will take coach for departure trip");
                }
            } else {
                confirmSeat();
            }
        });

    }
    var confirmSeat = function () {
        App.blockUI($("body"));
        $.ajax({
            method: 'POST',
            url: '/shortshift/LoadSeatSelected',
            data: $("#frSelectSeat").serialize(),
            success: function (data) {
                $(".seatselected").html(data);
                bindSelectSeatAgain();
            },
            error: function () {
                $(".seatselected").html("Lỗi trong quá trình chọn ghế.Vui lòng chọn lại.");
            }
        });
        $.ajax({
            method: 'POST',
            url: '/shortshift/LoadFormConfirmTicket',
            data: $("#frSelectSeat").serialize(),
            success: function (data) {
                $('.step3').html(data);
                $(".seatselected").slideDown();
                $('.step2').slideUp();
                $('.step3').slideDown();
                $('html,body').animate({
                    scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                }, 'slow');
                bindConfirmTicket();
                bindClickTransaction();
                bindClickPrivacy();
                gotostep(3);
                //checkPrivacy();
                //checkTransaction();
                $("#notecaptcha").hide();
                //App.unblockUI($('.pnlresulshortshift'));
                App.unblockUI($("body"));

            },
            error: function () {
                $('.step3').html("Load fail");
                //App.unblockUI($('.pnlresulshortshift'));
                App.unblockUI($("body"));
            }
        });
        return false;
    }
    var selectShift = function (shiftid, date, slot, routetype, dep, des) {
        if (slot > 4) {
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
                method: "POST",
                url: "/shortshift/loadseat",
                data: { shiftid: shiftid, date: date, routetype: routetype, departure: dep, destination: des },
                success: function (data) {
                    if (data.success === 0) {
                        Alert.Warning(data.message);
                    } else {
                        $(".step2").html(data);
                        $("html,body").animate({
                            scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                        }, "slow");
                        bindConfirmSeat();
                        bindSelectSeat();
                        gotostep(2);
                    }
                    App.unblockUI($("body"));
                },
                error: function () {
                    $(".step2").html("Load fail");
                    App.unblockUI($("body"));
                }
            });
        }
    }
    var selectShiftRoundTripSaving = function (shiftid, sdate, edate, slot, type) {
        if (slot > 4) {
            if (!$(this).hasClass("shiftdisabled")) {
                $(".blockshift").removeClass("dsgnmoo");
                $(".blockshift" + shiftid).addClass("dsgnmoo");
                $(".step2").slideDown();
                $(".step3").slideUp();
                $(".step4").slideUp();
                App.blockUI($(".resultshortshift"));
                $.ajax({
                    method: "POST",
                    url: "/shortshift/LoadSeatRoundTrip",
                    data: { shiftid: shiftid, startdate: sdate, enddate: edate, tickettype: type },
                    success: function (data) {
                        $(".step2").html(data);
                        $('html,body').animate({
                            scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                        }, 'slow');
                        bindChooseSeatRoundTripSaving();
                        bindConfirmSeatRoundTripSaving();
                        gotostep(2);
                        App.unblockUI($(".resultshortshift"));
                    },
                    error: function () {
                        $(".step2").html("Load fail");
                        App.unblockUI($(".resultshortshift"));
                    }
                });
            }
        }
    }
    var bindSelectShiftRoundTrip = function () {
        $(".btnSelectShiftRoundTrip").click(function () {
            var startshift = $("#hdstartshift").val();
            var endshift = $("#hdendshift").val();
            var culture = $("#culture").val();
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
            selectShiftRoundTrip();
            return false;
        });
    }
    var selectShiftRoundTrip = function () {
        App.blockUI($("body"));
        $.ajax({
            method: 'POST',
            url: '/shortshift/LoadSeatRoundTripOrdinary',
            data: $("#frSelectShiftRoundTrip").serialize(),
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".pnlresulshortshift").slideUp();
                    $(".step2").html(data);
                    $(".step2").slideDown();
                    $('html,body').animate({
                        scrollTop: $(".pnlresulshortshift").offset().top - $('.lightHeader').height()
                    }, 'slow');
                    bindChooseSeatRoundTrip();
                    bindConfirmSeatRoundTrip();
                    gotostep(2);
                }
                App.unblockUI($("body"));
            },
            error: function () {
                $(".step2").html("Load fail");
                $(".step2").slideDown();
                App.unblockUI($("body"));
            }
        });
        return false;
    };
    var bindChooseSeatRoundTrip = function () {
        $('.startseat').click(function () {
            var culture = $("#culture").val();
            if ($(this).hasClass('sselected')) {
                $(this).removeClass('sselected');
            } else {
                var countSeatSelected = $('.sselected').length;
                if (countSeatSelected < 3) {
                    $(this).addClass('sselected');
                }
            }
            $("#sseatselect").html("");
            $("#startseat").val("");
            var countBasic = 0;
            $('.startseat').each(function () {
                if ($(this).hasClass('sselected')) {
                    var idseat = $(this).attr('data-id');
                    var price = $(this).attr('data-price');
                    if (idseat == 0) {
                        countBasic++;
                    } else {
                        $("#sseatselect").append("VIP" + idseat + ",");
                    }
                    $("#startseat").val($("#startseat").val() + idseat + "_" + price + ",");
                }
            });
            $("#sseatselect").html($("#sseatselect").html().slice(0, -1) + '');
            $("#startseat").val($("#startseat").val().slice(0, -1) + '');
            if (countBasic > 0) {
                if (culture == "vi") {
                    if ($("#sseatselect").html() != '') {
                        $("#sseatselect").append(" và " + countBasic + " ghế thường");
                    } else {
                        $("#sseatselect").append(countBasic + " ghế thường");
                    }
                } else {
                    if ($("#sseatselect").html() != '') {
                        $("#sseatselect").append(" and " + countBasic + " basic" + (countBasic > 1 ? " seats" : " seat"));
                    } else {
                        $("#sseatselect").append(countBasic + " basic" + (countBasic > 1 ? " seats" : " seat"));
                    }
                }

            }
        });
        $('.endseat').click(function () {
            var culture = $("#culture").val();
            if ($(this).hasClass('eselected')) {
                $(this).removeClass('eselected');
            } else {
                var countSeatSelected = $('.eselected').length;
                if (countSeatSelected < 3) {
                    $(this).addClass('eselected');
                }
            }
            $("#eseatselect").html("");
            $("#endseat").val("");
            var countBasic = 0;
            $('.endseat').each(function () {
                if ($(this).hasClass('eselected')) {
                    var idseat = $(this).attr('data-id');
                    var price = $(this).attr('data-price');
                    if (idseat == 0) {
                        countBasic++;
                        //$("#seatselect").html($("#seatselect").html() + "," + countBasic + " Ghế thường");
                    } else {
                        $("#eseatselect").append("VIP" + idseat + ",");
                    }

                    //$("#seat").val(idseat + ",");
                    $("#endseat").val($("#endseat").val() + idseat + "_" + price + ",");
                }
            });
            $("#eseatselect").html($("#eseatselect").html().slice(0, -1) + '');
            $("#endseat").val($("#endseat").val().slice(0, -1) + '');
            if (countBasic > 0) {
                if (culture == "vi") {
                    if ($("#eseatselect").html() != '') {
                        $("#eseatselect").append(" và " + countBasic + " ghế thường");
                    } else {
                        $("#eseatselect").append(countBasic + " ghế thường");
                    }
                } else {
                    if ($("#eseatselect").html() != '') {
                        $("#eseatselect").append(" and " + countBasic + " basic" + (countBasic > 1 ? " seats" : " seat"));
                    } else {
                        $("#eseatselect").append(countBasic + " basic" + (countBasic > 1 ? " seats" : " seat"));
                    }
                }
            }
        });
    };
    var bindChooseSeatRoundTripSaving = function () {
        $('.seat').click(function () {
            var culture = $("#culture").val();
            //$('.seat').removeClass('selected');
            $(".note").removeClass('hidden');
            if ($(this).hasClass('sselected')) {
                $(this).removeClass('sselected');
            } else {
                var countSeatSelected = $('.sselected').length;
                if (countSeatSelected < 3) {
                    $(this).addClass('sselected');
                }
            }
            $("#seatselect").html("");
            $("#startseat").val("");
            var countBasic = 0;
            $('.seat').each(function () {
                if ($(this).hasClass('sselected')) {
                    var idseat = $(this).attr('data-id');
                    var price = $(this).attr('data-price');
                    if (idseat == 0) {
                        countBasic++;
                        //$("#seatselect").html($("#seatselect").html() + "," + countBasic + " Ghế thường");
                    } else {
                        $("#seatselect").append("VIP" + idseat + ",");
                    }

                    //$("#seat").val(idseat + ",");
                    $("#startseat").val($("#startseat").val() + idseat + "_" + price + ",");
                }
            });
            $("#seatselect").html($("#seatselect").html().slice(0, -1) + '');
            $("#startseat").val($("#startseat").val().slice(0, -1) + '');
            if (countBasic > 0) {
                if (culture == "vi") {
                    if ($("#seatselect").html() != '') {
                        $("#seatselect").append(" và " + countBasic + " ghế thường");
                    } else {
                        $("#seatselect").append(countBasic + " ghế thường");
                    }
                } else {
                    if ($("#seatselect").html() != '') {
                        $("#seatselect").append(" and " + countBasic + " basic" + (countBasic > 1 ? " seats" : " seat"));
                    } else {
                        $("#seatselect").append(countBasic + " basic" + (countBasic > 1 ? " seats" : " seat"));
                    }
                }

            }
        });
    };
    var bindConfirmSeatRoundTrip = function () {
        $(".btnSelectSeatRoundTrip").unbind('click');
        $(".btnSelectSeatRoundTrip").click(function (e) {
            e.preventDefault();
            var sSeat = $("#startseat").val();
            var eSeat = $("#endseat").val();
            var culture = $("#culture").val();
            var sCount = $('.sselected').length;
            var eCount = $('.eselected').length;
            if (sSeat == "") {
                if (culture == "vi") {
                    Alert.Warning("Quý khách vui lòng chọn ghế cho chuyến đi trước khi đặt vé.");
                } else {
                    Alert.Warning("Please choose departure seat before booking ticket");
                }
            } else if (eSeat == "") {
                if (culture == "vi") {
                    Alert.Warning("Quý khách vui lòng chọn ghế cho chuyến về trước khi đặt vé.");

                } else {
                    Alert.Warning("Please choose return seat before booking ticket");
                }
            } else if (sCount != eCount) {
                if (culture == "vi") {
                    Alert.Warning("Số lượng ghế chuyến đi và chuyến về phải bằng nhau.");
                    return false;
                } else {
                    Alert.Warning("Number of departure seats must be equal to number of return seats.");
                    return false;
                }
            } else {
                selectSeatRoundTrip();
            }
            return false;
        });
    }
    var bindConfirmSeatRoundTripSaving = function () {
        $(".btnSelectSeatRoundTrip").unbind('click');
        $(".btnSelectSeatRoundTrip").click(function (e) {
            e.preventDefault();
            var seat = $("#startseat").val();
            var culture = $("#culture").val();
            if (seat == "") {
                if (culture == "vi") {
                    Alert.Warning("Quý khách vui lòng chọn ghế trước khi đặt vé.");
                } else {
                    Alert.Warning("Please choose seat before booking ticket");
                }
            } else {
                selectSeatRoundTrip();
            }
            return false;
        });
    }
    var selectSeatRoundTrip = function () {
        App.blockUI($("body"));
        $.ajax({
            method: 'POST',
            url: '/shortshift/LoadFormConfirmTicketRoundTrip',
            data: $("#frSelectSeatRoundTrip").serialize(),
            success: function (data) {
                $('.step3').html(data);
                //$(".seatselected").slideDown();
                $('.step2').slideUp();
                $('.step3').slideDown();
                $('html,body').animate({
                    scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                }, 'slow');
                App.unblockUI($("body"));
                bindSelectSeatAgain();
                bindClickTransaction();
                bindClickPrivacy();
                //checkPrivacy();
                //checkTransaction();
                bindConfirmRoundTrip();
                gotostep(2);
                $("#notecaptcha").hide();
                //App.unblockUI($('.pnlresulshortshift'));
            }, error: function () {
                $('.step3').html("Load fail");
                App.unblockUI($("body"));
                //App.unblockUI($('.pnlresulshortshift'));
            }
        });
        return false;
    }

    var bindSelectSeatAgain = function () {
        $(".btnSelectSeatAgain").unbind('click');
        $(".btnSelectSeatAgain").click(function (e) {
            e.preventDefault();
            $(".step2").slideDown();
            $(".seatselected").slideUp();
            $(".step3").slideUp();
            $(".step4").slideUp();
            //$(".pnlresulshortshift").slideDown();
            $("html,body").animate({
                scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
            }, "slow");
            return false;
        });
    }
    var bindConfirmTicket = function () {
        $(".btnConfirmTicket").unbind('click');
        $(".btnConfirmTicket").click(function (e) {
            e.preventDefault();
            var captext = $("#CapImageText").val();
            var captextinput = $("#CaptchaCodeText").val();
            var name = $("#customername").val().trim();
            var phone = $("#phone").val().trim();
            var culture = $("#culture").val();
            if (name === '') {
                $("#customername").focus();
                if (culture === "vi") {
                    Alert.Warning("Quý khách vui lòng nhập đầy đủ họ tên trước khi đặt vé.");
                } else {
                    Alert.Warning("Please input your name before booking ticket.");
                }
                return false;
            }
            if (phone === '') {
                $("#phone").focus();
                if (culture === "vi") {
                    Alert.Warning("Quý khách vui lòng nhập số điện thoại trước khi đặt vé.");
                } else {
                    Alert.Warning("Please input your phonenumber before booking ticket.");
                }
                return false;
            }
            if (captext === captextinput) {
                confirmTicket();
            } else {
                $("#CaptchaCodeText").focus();
                $("#notecaptcha").show();
                $(".note").hide();
            }
            return false;
        });
    };
    var bindConfirmRoundTrip = function () {
        $(".btnConfirmRoundTrip").unbind('click');
        $(".btnConfirmRoundTrip").click(function (e) {
            e.preventDefault();
            var captext = $("#CapImageText").val();
            var captextinput = $("#CaptchaCodeText").val();
            var name = $("#customername").val().trim();
            var phone = $("#phone").val().trim();
            var culture = $("#culture").val();
            if (name == '') {
                $("#customername").focus();
                Alert.Warning(culture == "vi" ? "Vui lòng nhập đầy đủ họ tên trước khi đặt vé. Xin cảm ơn." : "Please input your name before booking ticket.");
                return false;
            }
            if (phone == '') {
                $("#phone").focus();
                Alert.Warning(culture == "vi" ? "Vui lòng nhập số điện thoại trước khi đặt vé. Xin cảm ơn." : "Please input your phonenumber before booking ticket.");
                return false;
            }
            if (captext == captextinput) {
                confirmRoundTrip();
            } else {
                $("#CaptchaCodeText").focus();
                $("#notecaptcha").show();
                $(".note").hide();
            }
            return false;
        });
    }
    var confirmTicket = function () {
        App.blockUI($("body"));
        var paymenttype = $("#frConfirmTicket input[name='paymenttype']:checked").val();
        $.ajax({
            method: 'POST',
            url: '/shortshift/ConfirmTicket',
            data: $("#frConfirmTicket").serialize(),
            success: function (data) {
                if (data.success === 0) {
                    Alert.Warning(data.message);
                } else {
                    // window.location = '/comingsoon';
                    $('.step2').slideUp();
                    $('.step1').slideUp();
                    $('.seatselected').slideUp();
                    $('.step3').slideUp();
                    $('.pnlresulshortshift').slideUp();
                    $('.step4').html(data);
                    $('.step4').slideDown();
                    gotostep(4);
                    if ($(".mainContentSection > .container").hasClass('maintb')) {
                        $('html, body').animate({
                            scrollTop: $(".mainContentSection > .container").position().top
                        }, 'slow');
                    } else {
                        $("html,body").animate({
                            scrollTop: $(".tab-content").offset().top - $('.lightHeader').height()
                        }, "slow");
                    }

                    var culture = $("#culture").val();
                    if (paymenttype != 5) {
                        bindPaymentSuccess(culture);
                    }
                   
                }
                App.unblockUI($("body"));
                //App.unblockUI($('.pnlresulshortshift'));
            }, error: function () {
                $('.step4').html("Load fail");
                App.unblockUI($("body"));
                //App.unblockUI($('.pnlresulshortshift'));
            }
        });
    }
    var confirmRoundTrip = function () {
        var paymenttype = $("#frConfirmRoundTripTicket input[name='paymenttype']:checked").val();
        App.blockUI($("body"));
        $.ajax({
            method: 'POST',
            url: '/shortshift/ConfirmTicketRoundTrip',
            data: $("#frConfirmRoundTripTicket").serialize(),
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    //window.location = '/comingsoon';
                    $('.step2').slideUp();
                    $('.step1').slideUp();
                    $('.seatselected').slideUp();
                    $('.step3').slideUp();
                    $('.pnlresulshortshift').slideUp();
                    $('.step4').html(data);
                    $('.step4').slideDown();
                    $("html,body").animate({
                        scrollTop: $(".tab-content").offset().top - $('.lightHeader').height()
                    }, "slow");
                    var culture = $("#culture").val();
                    if (paymenttype != 5) {
                        bindPaymentSuccess(culture);
                    }
                    
                    gotostep(4);
                }
                App.unblockUI($("body"));
                //App.unblockUI($('.pnlresulshortshift'));
            }, error: function () {
                $('.step4').html("Load fail");
                App.unblockUI($("body"));
                //App.unblockUI($('.pnlresulshortshift'));
            }
        });
        return false;
    }
    var bindClickPayment = function () {
        $(".btnPayment").unbind('click');
        $(".btnPayment").click(function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var transid = $(this).attr('data-transid');
            var link = $(this).attr('data-link');
            checkBeforePayment(id, transid, link);
        });
    };
    var bindClickCancelTicket = function () {
        $(".btnCancelTicket").unbind('click');
        $(".btnCancelTicket").click(function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var transid = $(this).attr('data-transid');
            var culture = $(this).attr('data-culture');
            cancelTicket(id, culture, transid);
        });
    };
    var bindPaymentSuccess = function (culture) {
        if (culture == "vi") {
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
    var cancelTicket = function (ticketid, culture, transid) {
        App.blockUI($("body"));
        $.ajax({
            url: "/shortshift/CancelAllTicket",
            data: { listordernumber: ticketid, transactionid: transid },
            success: function () {
                if (culture === "vi") {
                    Alert.Warning("Vé của bạn đã bị hủy.Vui lòng quay lại sau.");
                } else {
                    Alert.Warning("Your ticket was cancelled. Please come back later.");
                }
                $(".pnlresulshortshift").html("");
                $(".step4").slideUp();
                $(".step3").slideUp();
                $(".step2").slideUp();
                $(".step1").slideDown();
                $(".pnlresulshortshift").slideUp();
                App.unblockUI($("body"));
            },
            error: function () {
                if (culture === "vi") {
                    Alert.Warning("Vé của bạn đã bị hủy.Vui lòng quay lại sau.");
                } else {
                    Alert.Warning("Your ticket was cancelled. Please come back later.");
                }
                $(".pnlresulshortshift").html("");
                $(".step4").slideUp();
                App.unblockUI($("body"));
            }
        });
    }
    var checkBeforePayment = function (ticketid, transid, link) {
        App.blockUI($("body"));
        $.ajax({
            url: "/shortshift/UpdateAllWaitingPayment",
            data: { listordernumber: ticketid, transactionid: transid },
            success: function () {
                window.location = link;
                App.unblockUI($("body"));
            }
        });
    }
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
            $("#privacy").removeAttr('disabled');
        } else {
            $("#privacy").attr('disabled', 'disabled');
        }
    }
    var bindClickPrivacy = function () {
        $("#privacy").unbind('click');
        $("#privacy").click(function () {
            checkPrivacy();
        });
    };
    var checkPrivacy = function () {
        if ($("#privacy").is(":checked")) {
            $("#confirm-button").removeAttr('disabled');
        } else {
            $("#confirm-button").attr('disabled', 'disabled');
        }
    }
    var loadstationbyshift = function (shift, element, date) {
        $.ajax({
            url: "/shortshift/LoadStationByShift",
            data: { shift: shift, date: date },
            success: function (data) {
                $(element).html("");
                $.each(data.liststation,
                    function (index, option) {
                        $(element).append("<option value='" + option.id + "'>" + option.name + "</option>");
                    });
            }
        });
    }
    var gotostep = function (step) {
        //jQuery('html,body').animate({
        //    scrollTop: $(".progress-wizard").position().top
        //}, 'slow');
        if (step == 1) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("active");
            $(".progress-step-2").addClass("disabled");
            $(".progress-step-3").addClass("disabled");
            $(".progress-step-4").addClass("disabled");
            //$(".step1").slideDown();
            //$(".step2").slideUp();
            //$(".step3").slideUp();
            //$(".step4").slideUp();
        } else if (step == 2) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("active");
            $(".progress-step-3").addClass("disabled");
            $(".progress-step-4").addClass("disabled");
            //$(".step1").slideUp();
            //$(".step2").slideDown();
            //$(".step3").slideUp();
            //$(".step4").slideUp();
        } else if (step == 3) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("complete");
            $(".progress-step-3").addClass("active");
            $(".progress-step-4").addClass("disabled");
            //$(".step1").slideUp();
            //$(".step2").slideUp();
            //$(".step3").slideDown();
            //$(".step4").slideUp();
        } else if (step == 4) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("complete");
            $(".progress-step-3").addClass("complete");
            $(".progress-step-4").addClass("active");
            //$(".step1").slideUp();
            //$(".step2").slideUp();
            //$(".step3").slideUp();
            //$(".step4").slideDown();
        } else if (step == 5) {
            $(".progress-wizard-step").removeClass("complete");
            $(".progress-wizard-step").removeClass("active");
            $(".progress-wizard-step").removeClass("disabled");
            $(".progress-step-1").addClass("complete");
            $(".progress-step-2").addClass("complete");
            $(".progress-step-3").addClass("complete");
            $(".progress-step-4").addClass("complete");
            //$(".step1").slideUp();
            //$(".step2").slideUp();
            //$(".step3").slideUp();
            //$(".step4").slideDown();
        }
    };
    var init = function () {
        bindEvents();
        bindSearchShortShift();
    };
    return {
        Init: init
    };
})();