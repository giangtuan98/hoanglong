
    var bindEvents = function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        $(".tabbn a").click(function () {
            var tickettype = $(this).attr("data-tickettype");
            var div = $(this).attr("aria-controls");
            $(".tab-pane").html("");
            loadformseachshift(tickettype, div);
        });
        if ($(".bookinghcm .loadshifthcm").length) {
            loadshift();
        }
        //$('#datebook').datepicker({
        //    dateFormat: 'dd-mm-yy',
        //    minDate: "+1D",
        //    maxDate: "+60D",
        //    numberOfMonths: 1,
        //    showOtherMonths: false,
        //    selectOtherMonths: false,
        //    dayNamesMin: $("#datebook").attr("data-culture") == "vi" ? ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'] : ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
        //    monthNames: $("#datebook").attr("data-culture") == "vi" ? ["Tháng 1 -", "Tháng 2 -", "Tháng 3 -", "Tháng 4 -", "Tháng 5 -", "Tháng 6 -", "Tháng 7 -", "Tháng 8 -", "Tháng 9 -", "Tháng 10 -", "Tháng 11 -", "Tháng 12 -"] : ["January", "February", "March", "April", "May", "June",
        //	"July", "August", "September", "October", "November", "December"],
        //});
    };
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
            url: "/booking/LoadFormSearchShift",
            data: { tickettype: type },
            success: function (data) {
                $("#" + div).html(data);
                $(".step1").slideDown();
                $('html, body').animate({
                    scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                }, 'slow');
                App.unblockUI($("body"));
                bindSearchShiftRoundTrip();
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
        $(".btnSearchShift").unbind('click');
        $(".btnSearchShift").click(function (e) {
            e.preventDefault();
            var blank = $(this).attr("data-blank");
            var culture = $("#hdculture").val();
            var dep = $("#departure").val();
            var des = $("#destination").val();
            var quantity = $("#quantity").val();
            var depName = $("#departure option:selected").text();
            var desName = $("#destination option:selected").text();
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
            if (date == "") {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn ngày đi trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please choose date before search");
                }
                return false;
            }
            if (quantity == 0) {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn số lượng trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please choose quantity before search");
                }
                return false;
            }
            date = date.replace("-", "").replace("-", "").replace("-", "");
            if (dep == des) {
                if (culture == "vi") {
                    Alert.Warning("Điểm đi và điểm đến không được phép trùng nhau. Quý khách vui lòng chọn lại.");
                } else {
                    Alert.Warning("Departure and destination can't duplicate");
                }
                return false;
            }
            if (blank === "1") {
                document.forms['form'].submit();
                // window.open("/dat-ve-bac-nam/danh-sach-chuyen-tu-" + Core.UrlToSlug(depName) + "-den-" + Core.UrlToSlug(desName) + "/" + dep + "t" + des + "q" + quantity + "-" + date, '_blank');
            } else {
                //window.location = "/dat-ve-bac-nam/danh-sach-chuyen-tu-" + Core.UrlToSlug(depName) + "-den-" + Core.UrlToSlug(desName) + "/" + dep + "t" + des + "-" + date;
                loadshift();
            }

            return false;
        });
    };
    var bindSearchShiftRoundTrip = function () {
        $(".btnSearchShiftRoundTrip").unbind('click');
        $(".btnSearchShiftRoundTrip").click(function (e) {
            e.preventDefault();
            var culture = $(this).attr('data-culture');
            var dep = $("#departure").val();
            var des = $("#destination").val();
            var startdate = $("#frBookingRoundTrip input[name=startdate]").val();
            var enddate = $("#frBookingRoundTrip input[name=enddate]").val();
            var quantity = $("#frBookingRoundTrip #quantity").val();
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
            if (quantity == 0) {
                if (culture == "vi") {
                    Alert.Warning("Vui lòng chọn số lượng trước khi tìm chuyến");
                } else {
                    Alert.Warning("Please choose quantity before search");
                }
                return false;
            }
            loadShiftRoundTrip();
            return false;
        });
    };
    var loadShiftRoundTrip = function () {
        App.blockUI($("body"));
        $.ajax({
            method: 'POST',
            url: '/booking/LoadShiftRoundTrip',
            data: $('#frBookingRoundTrip').serialize(),
            success: function (data) {
                $(".pnlresulbooking").html(data);
                jQuery('html,body').animate({
                    scrollTop: $(".pnlresulbooking").offset().top - $('.lightHeader').height() //$(".pnlresulbookingcatba").position().top
                }, 'slow');
                bindSelectShiftRoundTrip();
                bindConfirmShiftRoundTrip();
                App.unblockUI($("body"));
            }, error: function () {
                $(".pnlresulbooking").html("Load fail");
                App.unblockUI($("body"));
            }
        });
        return false;
    }
    var bindSelectShiftRoundTrip = function () {
        $(".startshift").click(function () {
            var shift = $(this).attr("data-id");
            var price = $(this).attr("data-price");
            var date = $(this).attr("data-date");
            var eat = $(this).attr("data-eat");
            $("#hdstartshift").val(shift);
            $("#hdstartprice").val(price);
            $("#hdrealstartdate").val(date);
            $("#hdstarteat").val(eat);
            $('.startshift').removeClass('orange');
            $(this).addClass("orange");
            setTimeout(function () {
                if (eat === "True") {
                    $("#shift-" + shift + "-eat").tooltip('hide');
                } else {
                    $("#shift-" + shift).tooltip('hide');
                }
            }, 2000);
        });
        $(".endshift").click(function () {
            var shift = $(this).attr("data-id");
            var price = $(this).attr("data-price");
            var date = $(this).attr("data-date");
            var eat = $(this).attr("data-eat");
            $("#hdendshift").val(shift);
            $("#hdendprice").val(price);
            $("#hdrealenddate").val(date);
            $("#hdendeat").val(eat);
            $('.endshift').removeClass('orange');
            $(this).addClass("orange");
            setTimeout(function () {
                if (eat === "True") {
                    $("#shift-" + shift + "-eat").tooltip('hide');
                } else {
                    $("#shift-" + shift).tooltip('hide');
                }
            }, 2000);
        });
        $('.shift').each(function () {
            var eat = $(this).attr("data-eat");
            $(this).tooltip(
                {
                    html: true,
                    title: eat === "True" ? $('#shift-' + $(this).data('id') + "-eat").html() : $('#shift-' + $(this).data('id')).html()
                });
        });
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
            selectShiftRoundTrip(departure, destination, tickettype, startshift, endshift, startdate, enddate, startprice, endprice);
        });
    }
    var selectShiftRoundTrip = function (departure, destination, tickettype, startshift, endshift, startdate, enddate, startprice, endprice) {
        $.ajax({
            url: '/booking/LoadFormConfirmRoundTrip',
            data: $("#frSelectShiftRoundTrip").serialize(),
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step2").html(data);
                    $(".step1").slideUp();
                    $(".step2").slideDown();
                    //jQuery('html,body').animate({
                    //    scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
                    //}, 'slow');
                    //jQuery('html,body').animate({
                    //    scrollTop: $(".step2").position().top - $('.lightHeader').height()
                    //}, 'slow');
                    gotostep(2);
                    bindClickTransaction();
                    bindClickPrivacy();
                    bindConfirmInformation(1);
                    bindGoToStep();
                }

            }
        });
    }
    var bindSetVehicleId = function () {
        $(".btnSetVehicleId").unbind('click');
        $(".btnSetVehicleId").click(function () {
            var id = $(this).attr('data-id');
            var dep = $(this).attr('data-dep');
            var des = $(this).attr('data-des');
            var starttime = $(this).attr('data-starttime');
            var date = $(this).attr('data-date');
            var price = $(this).attr('data-price');
            var datecheck = $(this).attr('data-datecheck');
            var eat = $(this).attr('data-eat');
            var quantity = $(this).attr('data-quantity');
            setVehicleId(id, dep, des, starttime, date, price, datecheck, eat, quantity);
        });
    }
    var setVehicleId = function (vehicleid, dep, des, starttime, date, price, datecheck, eat, quantity) {
        App.blockUI($(".pnlresulbooking"));
        $.ajax({
            url: '/booking/loadformconfirm',
            data: { vehicleid: vehicleid, starttime: starttime, dep: dep, des: des, price: price, datev: date, datecheck: datecheck, eat: eat, quantity: quantity },
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step2").html(data);
                    $(".step1").slideUp();
                    $(".step2").slideDown();
                    bindClickTransaction();
                    bindClickPrivacy();
                    //checkPrivacy();
                    //checkTransaction();
                    bindConfirmInformation();
                    bindGoToStep();
                    gotostep(2);
                }
                App.unblockUI($(".pnlresulbooking"));
            },
            error: function () {
                App.unblockUI($(".pnlresulbooking"));
                Alert.Warning("Không thể chọn chuyến");
            }
        });
    };
    var bindConfirmInformation = function (isroundtrip) {
        $("#confirm-button").unbind('click');
        $("#confirm-button").click(function (e) {
            e.preventDefault();
            var culture = $(this).attr('data-culture');
            var checkFullname = $("#customername").val().trim();
            var checkPhone = $("#phone").val().trim();
            var checkEmail = $("#email").val().trim();
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
            if (isroundtrip) {
                loadSeatRoundTrip();
            } else {
                loadSeat();
            }

            return false;
        });
    }
    var loadSeatRoundTrip = function () {
        App.blockUI($("body"));
        $.ajax({
            method: "POST",
            url: "/booking/loadseatroundtrip",
            data: $("#frBookingLoadSeatRoundTrip").serialize(),
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step3").html(data);
                    bindSelectSeatRoundTrip();
                    bindConfirmTicket(1);
                    bindGoToStep();
                    $("#notecaptcha").hide();
                    gotostep(3);
                }
                App.unblockUI($("body"));
            }, error: function () {
                Alert.Warning("Xảy ra lỗi.");
                App.unblockUI($("body"));
            }
        });
        return false;
    }
    var loadSeat = function () {
        App.blockUI($("body"));
        $.ajax({
            method: "POST",
            url: "/booking/loadseat",
            data: $("#frBookingStep2").serialize(),
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step3").html(data);
                    bindSelectSeat();
                    bindConfirmTicket();
                    bindGoToStep();
                    $("#notecaptcha").hide();
                    gotostep(3);
                }
                App.unblockUI($("body"));
            }, error: function () {
                Alert.Warning("Xảy ra lỗi.");
                App.unblockUI($("body"));
            }
        });
        return false;
    }
    var bindConfirmTicket = function (isroundtrip) {
        $(".btnConfirmTicket").unbind('click');
        $(".btnConfirmTicket").click(function () {
            var culture = $(this).attr('data-culture');
            var paymenttype = $("#paymenttype").val();
            var captext = $("#CapImageText").val();
            var captextinput = $("#CaptchaCodeText").val();
            var seat = $("#seat").val();
            if (!isroundtrip) {
                var seatSelected = $('#frBookingStep3 .seat.orange').length;
                var quantity = $("#frBookingStep2 input[name='quantity']").val();
                if (seatSelected != 0 && seatSelected > quantity) {
                    if (culture == "vi") {
                        Alert.Warning("Số lượng ghế đã chọn vượt quá số lượng đã chọn");
                    } else {
                        Alert.Warning("The number of seats selected exceeds the selected quantity");
                    }
                    return false;
                }
                if (seatSelected != 0 && seatSelected < quantity) {
                    if (culture == "vi") {
                        Alert.Warning("Số lượng ghế đã chọn ít hơn số lượng đã chọn");
                    } else {
                        Alert.Warning("The number of seats selected is less than the selected quantity");
                    }
                    return false;
                }
            }
            else {
                var startSeatSelected = $('#frBookingConfirmRoundTrip .startseat.orange').length;
                console.log("startSeatSelected: " + startSeatSelected);
                var endSeatSelected = $('#frBookingConfirmRoundTrip .endseat.orange').length;
                console.log("endSeatSelected: " + endSeatSelected);
                var quantity = $("#frBookingConfirmRoundTrip input[name='quantity']").val();
                console.log("quantity: " + quantity);
                if (startSeatSelected != 0 && startSeatSelected > quantity) {
                    if (culture == "vi") {
                        Alert.Warning("Số lượng ghế chuyến đi đã chọn vượt quá số lượng đã chọn");
                    } else {
                        Alert.Warning("The number of seats selected exceeds the selected quantity");
                    }
                    return false;
                }
                else if (startSeatSelected != 0 && startSeatSelected < quantity) {
                    if (culture == "vi") {
                        Alert.Warning("Số lượng ghế chuyến đi đã chọn ít hơn số lượng đã chọn");
                    } else {
                        Alert.Warning("The number of seats selected is less than the selected quantity");
                    }
                    return false;
                }
                else if (endSeatSelected != 0 && endSeatSelected < quantity) {
                    if (culture == "vi") {
                        Alert.Warning("Số lượng ghế chuyến về đã chọn ít hơn số lượng đã chọn");
                    } else {
                        Alert.Warning("The number of seats selected is less than the selected quantity");
                    }
                    return false;
                }
                else if (endSeatSelected != 0 && endSeatSelected > quantity) {
                    if (culture == "vi") {
                        Alert.Warning("Số lượng ghế chuyến về đã chọn vượt quá số lượng đã chọn");
                    } else {
                        Alert.Warning("The number of seats selected exceeds the selected quantity");
                    }
                    return false;
                }
            }

            if (captext != captextinput) {
                $("#notecaptcha").show();
                $(".note").hide();
            } else {
                if (paymenttype === 3 || paymenttype === 4 || paymenttype === 5) {
                    if (seat === "") {
                        if (culture === "vi") {
                            Alert.Warning("Bạn vui lòng chọn ghế trước khi đặt vé");
                        } else {
                            Alert.Warning("Please choose seat before booking ticket");
                        }
                    } else {
                        if (isroundtrip === 1) {
                            confirmTicketRoundTrip();
                        } else {
                            confirmTicket();
                        }
                    }
                } else {
                    if (isroundtrip === 1) {
                        confirmTicketRoundTrip();
                    } else {
                        confirmTicket();
                    }
                }
            }
        });
    }
    var confirmTicket = function () {
        App.blockUI("body");
        var paymenttype = $("#frBookingStep3 input[name='paymenttype']").val();
        var culture = $("#frBookingStep2 #hdculture").val();
        $.ajax({
            method: "POST",
            url: '/booking/confirmticket',
            data: $("#frBookingStep3").serialize(),
            success: function (data) {
                if (data.success == 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step4").html(data);
                    gotostep(4);
                    if (paymenttype == 3 || paymenttype == 4) {
                        bindPaymentSuccess(culture);
                    }
                }
                App.unblockUI("body");
            }, error: function () {
                Alert.Warning("Error.");
                App.unblockUI("body");
            }
        });
        return false;
    }
    var confirmTicketRoundTrip = function () {
        App.blockUI("body");
        var paymenttype = $("#frBookingConfirmRoundTrip input[name='paymenttype']").val();
        var culture = $("#frBookingRoundTrip #hdculture").val();
        $.ajax({
            method: "POST",
            url: '/booking/confirmticketroundtrip',
            data: $("#frBookingConfirmRoundTrip").serialize(),
            success: function (data) {
                if (data.success === 0) {
                    Alert.Warning(data.message);
                } else {
                    $(".step4").html(data);
                    gotostep(4);
                    if (paymenttype == 3 || paymenttype == 4) {
                        bindPaymentSuccess(culture);
                    }
                }
                App.unblockUI("body");
            }, error: function () {
                Alert.Warning("Error.");
                App.unblockUI("body");
            }
        });
        return false;
    }
    var bindSelectSeat = function () {
        $('.seat').unbind('click');
        $('.seat').click(function () {
            //var idseat = $(this).attr('data-id');
            //$('#seatselect').html(idseat);
            //$('#seat').val(idseat);
            //$('.seat').removeClass('orange');
            //$(".note").removeClass('hidden');
            //$(this).addClass('orange');

            //Nếu seat đang được chọn, thì click lại sẽ remove class và bỏ chọn, Nếu không add class orange đánh dấu là được chọn
            var quantity = $("#frBookingStep3 input[name='quantity']").val();

            if ($(this).hasClass("orange")) {
                $(this).removeClass("orange");
            }
            else {
                $(this).addClass('orange');
            }
            var seatSelected = $('#frBookingStep3 .seat.orange').length;
            if (checkSelectSeatByQuantity(quantity, seatSelected)) {

                //Loop qua những thằng seat được chọn và lấy ra list id để set giá trị cho các biến
                var listIdSeat = "";
                $('#frBookingStep3 .seat.orange').each(function (index, value) {
                    if (index == 0) {
                        listIdSeat += $(this).attr('data-id');
                    }
                    else {
                        listIdSeat += "," + $(this).attr('data-id');
                    }
                    $('#seatselect').html(listIdSeat);
                    $('#seat').val(listIdSeat);
                    $(".note").removeClass('hidden');
                });
            }
            else {
                $(this).removeClass("orange");
            }
        });

    }
    var bindSelectSeatRoundTrip = function () {
        $('.startseat').unbind('click');
        $('.startseat').click(function () {
            //var idseat = $(this).attr('data-id');
            //$('#startseatselect').html(idseat);
            //$('#startseat').val(idseat);
            //$('.startseat').removeClass('orange');
            //$(".startnote").removeClass('hidden');
            //$(this).addClass('orange');

            //Check nếu số lượng ghế chọn vượt quá số lượng đã chọn ban đầu
            if ($(this).hasClass("orange")) {
                $(this).removeClass("orange");
            }
            else {
                $(this).addClass('orange');
            }

            var quantity = $("input[name='quantity']").val();
            var seatSelected = $('.startseat.orange').length;
            if (checkSelectSeatByQuantity(quantity, seatSelected)) {
                //Loop qua những thằng seat được chọn và lấy ra list id để set giá trị cho các biến
                var listIdSeat = "";
                $('.startseat.orange').each(function (index, value) {
                    if (index == 0) {
                        listIdSeat += $(this).attr('data-id');
                    }
                    else {
                        listIdSeat += "," + $(this).attr('data-id');
                    }
                    $('#startseatselect').html(listIdSeat);
                    $('#startseat').val(listIdSeat);
                    $(".startnote").removeClass('hidden');
                });
            }
            else {
                $(this).removeClass("orange");
            }
        });
        $('.endseat').unbind('click');
        $('.endseat').click(function () {
            //var idseat = $(this).attr('data-id');
            //$('#endseatselect').html(idseat);
            //$('#endseat').val(idseat);
            //$('.endseat').removeClass('orange');
            //$(".endnote").removeClass('hidden');
            //$(this).addClass('orange');

            //Check nếu số lượng ghế chọn vượt quá số lượng đã chọn ban đầu
            if ($(this).hasClass("orange")) {
                $(this).removeClass("orange");
            }
            else {
                $(this).addClass('orange');
            }

            var quantity = $("input[name='quantity']").val();
            var seatSelected = $('.endseat.orange').length;
            if (checkSelectSeatByQuantity(quantity, seatSelected)) {

                //Loop qua những thằng seat được chọn và lấy ra list id để set giá trị cho các biến
                var listIdSeat = "";
                $('.endseat.orange').each(function (index, value) {
                    if (index == 0) {
                        listIdSeat += $(this).attr('data-id');
                    }
                    else {
                        listIdSeat += "," + $(this).attr('data-id');
                    }
                    $('#endseatselect').html(listIdSeat);
                    $('#endseat').val(listIdSeat);
                    $(".endnote").removeClass('hidden');
                });
            }
            else {
                $(this).removeClass("orange");
            }
        });
    }

    var checkSelectSeatByQuantity = function (quantity, seatSelected) {
        console.log("quantity: " + quantity);
        console.log("seatSelected: " + seatSelected);
        var flag = true;
        if (quantity < seatSelected) {
            var culture = $("#hdculture").val();
            if (culture == "vi") {
                Alert.Warning("Bạn đã chọn đủ số ghế");
            } else {
                Alert.Warning("You have selected enough seats");
            }
            flag = false;
        }
        return flag;
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
    };
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
    };
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
    var loadshift = function () {
        var dep = $("#departure").val();
        var des = $("#destination").val();
        //var api = $("#frBooking1 input[name=isAPI").val();
        var culture = $("#hdculture").val();
        if (dep == des) {
            if (culture == "vi") {
                Alert.Warning("Điểm đi và điểm đến không thể trùng nhau");
            } else {
                Alert.Warning("Departure and destination can't duplicate");
            }
            return false;
        }
        var date = $("#datebook").val();
        console.log("date:" + date);
        var quantity = $("#quantity").val();
        // App.blockUI($("body"));
        // $.ajax({
        //     method: 'POST',
        //     url: '/booking/LoadShift',
        //     data: {
        //         departure: dep,
        //         destination: des,
        //         date: date,
        //         quantity: quantity
        //     },
        //     success: function (data) {

        //         $(".pnlresulbooking").html(data);
        //         jQuery('html,body').animate({
        //             scrollTop: $(".pnlresulbooking").offset().top - $('.lightHeader').height()
        //         }, 'slow');
        //         bindSetVehicleId();
        //         App.unblockUI($("body"));
        //     },
        //     error: function () {
        //         $(".pnlresulbooking").html("");
        //         App.unblockUI($("body"));
        //     }
        // });
        return false;
    };
    var bindGoToStep = function () {
        $(".btnGoToStep").unbind('click');
        $(".btnGoToStep").click(function (e) {
            e.preventDefault();
            var step = $(this).attr('data-step');
            gotostep(step);
        });
    };

    var GoToPayment = function () {
        App.blockUI($("body"));
        var href = $(".payment-href-hcm").val();
        location.href = href;
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
                    cancelTicket(culture);
                }
            });
        });
    };

    var cancelTicket = function (culture) {
        App.blockUI();
        if (culture === "vi") {
            Alert.Warning("Vé của bạn đã bị hủy.Vui lòng quay lại sau.");
        } else {
            Alert.Warning("Your ticket was cancelled. Please come back later.");
        }
        $(".pnlresulbooking").html("");
        $(".step4").slideUp();
        $(".step3").slideUp();
        $(".step2").slideUp();
        $(".step1").slideDown();
        $(".pnlresulbooking").slideUp();
        App.unblockUI();
    };

    var init = function () {
        bindEvents();
        bindClickSearchShift();
    };


