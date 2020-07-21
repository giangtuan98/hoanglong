var bill, user_info, code, data_email;
let culture;
let selectedSeat = [];
$(".btnGoToStep").unbind('click');
$(".btnGoToStep").click(function (e) {
    e.preventDefault();
    var step = $(this).attr('data-step');
    gotostep(step);
});
function createCaptcha() {
    //clear the contents of captcha div first 
    document.getElementById('captcha').innerHTML = "";
    var charsArray =
        "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%^&*";
    var lengthOtp = 6;
    var captcha = [];
    for (var i = 0; i < lengthOtp; i++) {
        //below code will not allow Repetition of Characters
        var index = Math.floor(Math.random() * charsArray.length + 1); //get the next character from the array
        if (captcha.indexOf(charsArray[index]) == -1)
            captcha.push(charsArray[index]);
        else i--;
    }
    var canv = document.createElement("canvas");
    canv.id = "captcha";
    canv.width = 100;
    canv.height = 50;
    var ctx = canv.getContext("2d");
    ctx.font = "25px Georgia";
    ctx.strokeText(captcha.join(""), 0, 30);
    //storing captcha so that can validate you can save it somewhere else according to your specific requirements
    code = captcha.join("");
    document.getElementById("captcha").appendChild(canv); // adds the canvas to the body element
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

$(".btnSetVehicleId").unbind('click');
$(".btnSetVehicleId").click(function () {
    bill = {
        brandId: $(this).attr('data-brand-id'),
        brandName: $(this).attr('data-brand-name'),
        tddId: $(this).attr('data-tdd-id'),
        departId: $(this).attr('data-depart-id'),
        departName: $(this).attr('data-depart-name'),
        departTime: $(this).attr('data-depart-time'),
        desId: $(this).attr('data-des-id'),
        desName: $(this).attr('data-des-name'),
        desTime: $(this).attr('data-des-time'),
        date: $(this).attr('data-date'),
        price: $(this).attr('data-price'),
        dateCheck: $(this).attr('data-datecheck'),
        quantity: $(this).attr('data-quantity'),
        routeName: $(this).attr('data-route-name'),
        depProvinceName: $(this).attr('data-dep-province-name'),
        desProvinceName: $(this).attr('data-des-province-name'),
        tripId: $(this).attr('data-trip-id'),
        tripName: $(this).attr('data-trip-name')
    }
    gotostep(2);
});

var gotostep = function (step) {
    jQuery('html,body').animate({
        scrollTop: $(".positiontop").offset().top - $('.lightHeader').height()
    }, 'slow');
    selectedSeat = [];
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

$('.transaction input[type="radio"]').each(function () {
    $(this).unbind('click');
    $(this).click(function () {
        var type = $(this).val();
        checkTransaction(type);
    });
});

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

$("#privacy").unbind('click');
$("#privacy").click(function () {
    checkPrivacy();
});

var checkPrivacy = function () {
    if ($("#privacy").is(":checked")) {
        $("#confirm-button").removeAttr('disabled');
    } else {
        $("#confirm-button").attr('disabled', 'disabled');
    }
};
$("#confirm-button").unbind('click');
$("#confirm-button").click( async function (e) {
    e.preventDefault();
    culture = $(this).attr('data-culture');
    var checkFullName = $("#passengerName").val().trim();
    var checkPhone = $("#passengerPhone").val().trim();
    var checkEmail = $("#passengerEmail").val().trim();
    if (checkFullName == '') {
        $("#passengerName").focus();
        if (culture == "vi") {
            Alert.Warning("Xin vui lòng điền tên của bạn.");
        } else {
            Alert.Warning("Please fill your name.");
        }
        return false;
    }
    if (checkPhone == '') {
        $("#passengerPhone").focus();
        if (culture == "vi") {
            Alert.Warning("Xin vui lòng điền số điện thoại của bạn.");
        } else {
            Alert.Warning("Please fill your phone.");
        }
        return false;
    }
    if (checkEmail == '') {
        $("#passengerEmail").focus();
        if (culture == "vi") {
            Alert.Warning("Xin vui lòng điền email của bạn.");
        } else {
            Alert.Warning("please fill your email");
        }
        return false;
    }
    if (!validateEmail(checkEmail)) {
        $("#passengerEmail").focus();
        if (culture == "vi") {
            Alert.Warning("Email không đúng định dạng.");
        } else {
            Alert.Warning("Email invalidate");
        }
        return false;
    }
    bill = {
        ...bill,
        passengerName: $("#passengerName").val(),
        passengerPhone: $("#passengerPhone").val(),
        passengerEmail: $("#passengerEmail").val(),
        passengerAddress: $("#passengerAddress").val(),
        paymenttype: $("input[type='radio'][name='paymenttype']:checked").val()
    }
    $("#passengerNameHd").val(bill.passengerName);
    $("#passengerPhoneHd").val(bill.passengerPhone);
    $("#passengerEmailHd").val(bill.passengerEmail);
    $("#passengerAddressHd").val(bill.passengerAddress);
    $("#priceHd").val(bill.price);
    $("#quantityHd").val(bill.quantity);
    $("#dateHd").val(bill.date);
    $("#paymenttypeHd").val(bill.paymenttype);
    $("#tddIdHd").val(bill.tddId);
    $("#brandIdHd").val(bill.brandId);
    $("#departNameHd").val(bill.departName);
    $("#departTimeHd").val(bill.departTime);
    $("#desNameHd").val(bill.desName);
    $("#desTimeHd").val(bill.desTime);
    $("#routeNameHd").val(bill.routeName);
    $("#depProvinceNameHd").val(bill.depProvinceName);
    $("#desProvinceNameHd").val(bill.desProvinceName);
    $("#selectedSeatsHd").val(selectedSeat.join(','));
    $("#tripNameHd").val(bill.tripName);

    $("#passengerNameTbl").text(bill.passengerName);
    $("#passengerPhoneTbl").text(bill.passengerPhone);
    $("#passengerEmailTbl").text(bill.passengerEmail);
    $("#passengerAddressTbl").text(bill.passengerAddress);
    $("#departProvinceTbl").text(bill.depProvinceName + " - ( " + bill.departName + " )");
    $("#desProvinceTbl").text(bill.desProvinceName + " - ( " + bill.desName + " )");
    $("#priceTbl").text(formatNumber(bill.price));
    $("#quantityTbl").text(formatNumber(bill.quantity));
    $("#totalTbl").text(formatNumber(bill.price * bill.quantity));
    $("#routeNameTbl").text(bill.routeName);
    $("#dateTbl").text(bill.departTime + ' ' + bill.date);
    $("#brandNameTbl").text(bill.brandName);
    if (bill.paymenttype == 1) {
        let text = "Giao dịch trực tiếp";
        if (culture == "en") {
            text = "Direct payment";
        }
        $("#paymenttypeTbl").text(text);
    }
    createCaptcha();

    await getPickupPlaces(bill.tripId);
    await getSeatMap(bill.tddId);
    gotostep(3);
    return false;
});
$("#btnSendCodeAgain").unbind('click');
$("#btnSendCodeAgain").click(function (e) {
    e.preventDefault();
    sendConfirmCodeText(data_email);
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function displayError(errors) {
    // var errors = data.responseJSON.errors;
    var firstItem = Object.keys(errors)[0];
    var firstItemDOM = document.getElementById(firstItem);
    var firstErrorMessage = errors[firstItem][0];
    firstItemDOM.scrollIntoView({ behavior: "smooth", inline: "nearest" });
    clearErrorText();
    firstItemDOM.insertAdjacentHTML('afterend', `<p class="text-danger" style="font-size: 13px; margin:0;">* ${firstErrorMessage}</div>`)
    clearBorderError();
    firstItemDOM.classList.add('border', 'border-danger');
}

function clearErrorText() {
    var errorMessages = document.querySelectorAll('.text-danger');
    errorMessages.forEach((element) => element.textContent = '');
}
function clearBorderError() {
    var formControls = document.querySelectorAll('.form-control')
    formControls.forEach((element) => element.classList.remove('border', 'border-danger'));
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}

$(".btnConfirmTicket").unbind('click');
$(".btnConfirmTicket").click(function () {
    var confirmCodeText = document.getElementById('CaptchaCodeText').value;
    if (selectedSeat.length < bill.quantity) {
        Alert.Warning("Bạn chưa chọn đủ số lượng ghế");
        return;
    }
    if (confirmCodeText === code) {
        $("#notecaptcha").hide();
        const form = $('#form_step3');
        $('#pageLoading').addClass('show');
        $("#selectedSeatsHd").val(selectedSeat.join(','));
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: {
                formData: form.serialize(),
            },
            success: function (data) {
                console.log(data);
                
                if (data.status === "success") {
                    $("#step4render").append(data.data.view);
                    $('#pageLoading').removeClass('show');
                    gotostep(4);
                    Alert.Success(data.message);
                } else {
                    Alert.Error(data.message);
                    $('#pageLoading').removeClass('show');
                }
            },
            error: function (data, textStatus, errorThrown) {
                var response = JSON.parse(data.responseText);
                console.log(data);
                // displayError(response.errors);
            },
        });
    }
    else {
        if (culture == 'vi') {
            $("#notecaptcha").html("Mã xác nhận không đúng");
        } else {
            $("#notecaptcha").html("Incorrect code");
        }

        $("#notecaptcha").show();
        document.getElementById('notecaptcha').style.display = 'block';
    }

});

function getPickupPlaces (tripId) {
    
    $.ajax({
        url: "/get-pickup-places",
        type: 'post',
        data: {tripId : tripId},
        success: function (res) {
            console.log(res);
            
            if (res.status === 200) {
                $("#slPickupPlace").empty();

                res.data.map((schedule, index) => {
                    const {time, location} = schedule;
                    let option = '';
                    if (index === 0) {
                        $("#mapPickupPlace").attr("src", location.map_url);
                        option = `<option value="${location.id}" selected data-map-url="${location.map_url}" data-name="${location.name}" data-time=${time}>${time} - ${location.name}</option>`;
                        $("#pickupPlaceHd").val(location.id);
                        $("#pickupPlaceNameHd").val(location.name);
                        $("#pickupTimeHd").val(time);
                        $("#pickupPlaceUrlHd").val(location.map_url);
                    } else {
                        option = `<option value="${location.id}" data-map-url="${location.map_url}" data-time=${time}>${time} - ${location.name}</option>`;
                    }
                    
                    $("#slPickupPlace").append(option);
                });
            }
        },
        error: function (error) {
            console.log(error);
            
        }
    })
}

$("#slPickupPlace").change(function () {
    const mapUrl = $("#slPickupPlace option:selected").attr('data-map-url');
    $("#mapPickupPlace").attr("src", mapUrl);
    $("#pickupPlaceHd").val($(this).val());
    $("#pickupPlaceNameHd").val($("#slPickupPlace option:selected").attr('data-name'));
    $("#pickupTimeHd").val($("#slPickupPlace option:selected").attr('data-time'));
    $("#pickupPlaceUrlHd").val(mapUrl);
})

$('.book-seat').click(function () {
    bookSeat($(this));
});

function bookSeat() {
    const quantity = parseInt(bill.quantity);
  
  
  const index = selectedSeat.indexOf($(this).attr('data-id'));
  if (index === -1) {
    if (selectedSeat.length >= quantity) {
        if (culture == "vi") {
            Alert.Warning("Số lượng ghế đã vượt quá giới hạn.");
        } else {
            Alert.Warning("The number of seats has exceeded the limit.");
        }
        return;
    }
    selectedSeat.push($(this).attr('data-id'));
    $(this).toggleClass('seat-default');
    $(this).toggleClass('seat-blue');
  } else {
    selectedSeat = [ ...selectedSeat.slice(0,index), ...selectedSeat.slice(index+1)];
    $(this).toggleClass('seat-default');
    $(this).toggleClass('seat-blue');
  }
  $('#selectedSeat').text(selectedSeat.join(','));
  $("#selectedSeatsHd").val(selectedSeat.join(','));
}

const getSeatMap = (tddId) => {
    $.ajax({
        url: "/get-seat-map",
        type: 'post',
        data: {tddId : tddId},
        success: function (res) {
            if (res.status === 200) {
                $("#list_seat").empty();
                $("#list_seat").append(res.data.view);
                $(".book-seat").on('click', bookSeat);
            }
        },
        error: function (error) {
            console.log(error);
            
        }
    })
}

// $("#btnSearchShift1").click(function (e) {
//     e.preventDefault();

//     if (!$("#select2-departure-container").val()) {
//         Alert.Warning('')
//     }
// })