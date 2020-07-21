function dicPage(page) {
    let word = '';
    switch (page) {
        case 'role':
            word = 'quyền';
            break;
        case 'user':
            word = 'người dùng';
            break;
        case 'brand':
            word = 'nhà xe';
            break;
        case 'place':
            word = 'địa điểm';
            break;
        case 'trip':
            word = 'chuyến';
            break;
        case 'route':
            word = 'tuyến đường';
            break;
        case 'ticket':
            word = 'vé';
            break;
        case 'office':
            word = 'văn phòng';
            break;
        case 'province':
            word = 'tỉnh thành';
            break;
        case 'district':
            word = 'quận huyện';
        default:
            break;
    };
    return word;
}

$(".btn-cancel").click(function(e) {
    e.preventDefault();
    Swal.fire({
        title: "Xác nhận rời khỏi trang này?",
        text: "Các thay đổi sẽ không được cập nhật!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Xác nhận",
        cancelButtonText: "Ở lại trang này"
    }).then(result => {
        if (result.value) {
            window.location.href = $(this).attr("data-next-route");
        }
    });
});
$(".btn-deactivate").click(function(e) {
    e.preventDefault();
    const page = $(this).attr('data-page');

    Swal.fire({
        title: "Xác nhận ngưng kích hoạt?",
        text: `Bạn có muốn ngưng kích hoạt ${dicPage(page)} này không!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Xác nhận",
        cancelButtonText: "Hủy"
    }).then(result => {
        if (result.value) {
            window.location.href = $(this).attr("href");
        }
    });
});
$(".btn-active").click(function(e) {
    e.preventDefault();
    const page = $(this).attr('data-page');

    Swal.fire({
        title: "Xác nhận tái kích hoạt?",
        text: `Bạn có muốn tái kích hoạt ${dicPage(page)} này không!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Xác nhận",
        cancelButtonText: "Hủy"
    }).then(result => {
        if (result.value) {
            window.location.href = $(this).attr("href");
        }
    });
});

$(".btn-delete").click(function(e) {
    e.preventDefault();
    const page = $(this).attr('data-page');

    Swal.fire({
        title: "Xác nhận xóa?",
        text: `Bạn có muốn xóa ${dicPage(page)} này không!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Xác nhận",
        cancelButtonText: "Hủy"
    }).then(result => {
        if (result.value) {
            window.location.href = $(this).attr("href");
        }
    });
});

$(document).ready( function () {
    setTimeout(() => {
        $(".flash-alert").slideUp();
    }, 3000);
});

//Format money in textbox
function cms_encode_currency_format(num) {
    if (num < 0) {
        return 0;
    }

    if (isNaN(num)) {
        return '';
    }

    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}

function cms_decode_currency_format(obs) {
    return parseInt(obs.replace(/,/g, ''));
}

$('.number-format').keyup(function () {
    let value = $(this).val();
    if (value == '') {
        value = '';
    } else {
        value = cms_decode_currency_format(value);
        value = cms_encode_currency_format(value);
    }
    $(this).val(value);
});


