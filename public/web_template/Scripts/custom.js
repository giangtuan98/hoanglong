Custom = (function () {
    var bindEvents = function () {
        $(".jqueryuidatepicker").datepicker({
            dateFormat: $(".jqueryuidatepicker").attr('data-format'),
            minDate: $(".jqueryuidatepicker").attr('data-mindate'),
            maxDate: $(".jqueryuidatepicker").attr('data-maxdate'),
            showOtherMonths: true,
            selectOtherMonths: true,
            dayNamesMin: $(".jqueryuidatepicker").attr("data-culture") == "vi" ? ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'] : ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            monthNames: $(".jqueryuidatepicker").attr("data-culture") == "vi" ? ["Tháng 1 -", "Tháng 2 -", "Tháng 3 -", "Tháng 4 -", "Tháng 5 -", "Tháng 6 -", "Tháng 7 -", "Tháng 8 -", "Tháng 9 -", "Tháng 10 -", "Tháng 11 -", "Tháng 12 -"] : ["January", "February", "March", "April", "May", "June",
			"July", "August", "September", "October", "November", "December"],
        });
        $('.startdatejqueryui').datepicker({
            dateFormat: $(".startdatejqueryui").attr('data-format'),
            minDate: $(".startdatejqueryui").attr('data-mindate'),
            maxDate: $(".startdatejqueryui").attr('data-maxdate'),
            numberOfMonths: 1,
            showOtherMonths: false,
            selectOtherMonths: false,
            dayNamesMin: $(".startdatejqueryui").attr("data-culture") == "vi" ? ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'] : ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            monthNames: $(".startdatejqueryui").attr("data-culture") == "vi" ? ["Tháng 1 -", "Tháng 2 -", "Tháng 3 -", "Tháng 4 -", "Tháng 5 -", "Tháng 6 -", "Tháng 7 -", "Tháng 8 -", "Tháng 9 -", "Tháng 10 -", "Tháng 11 -", "Tháng 12 -"] : ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"],
            onClose: function (selectedDate) {
                if (selectedDate != '') {
                    //$(".enddatejqueryui").datepicker("option", "minDate", selectedDate);
                    var date2 = $('.startdatejqueryui').datepicker('getDate');
                    date2.setDate(date2.getDate() + 1);
                    $(".enddatejqueryui").datepicker("option", "minDate", date2);
                }
            }
        });
        $(".enddatejqueryui").datepicker({
            dateFormat: $(".enddatejqueryui").attr('data-format'),
            minDate: $(".enddatejqueryui").attr('data-mindate'),
            maxDate: $(".enddatejqueryui").attr('data-maxdate'),
            numberOfMonths: 1,
            showOtherMonths: false,
            selectOtherMonths: false,
            dayNamesMin: $(".enddatejqueryui").attr("data-culture") == "vi" ? ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'] : ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            monthNames: $(".enddatejqueryui").attr("data-culture") == "vi" ? ["Tháng 1 -", "Tháng 2 -", "Tháng 3 -", "Tháng 4 -", "Tháng 5 -", "Tháng 6 -", "Tháng 7 -", "Tháng 8 -", "Tháng 9 -", "Tháng 10 -", "Tháng 11 -", "Tháng 12 -"] : ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"],
            onClose: function (selectedDate) {
                if (selectedDate != '') {
                    $(".startdatejqueryui").datepicker("option", "maxDate", selectedDate);
                }
            }
        });
        $(".niceselect").niceSelect();
        $(".select2bootstrap").select2();
        $('[data-toggle="tooltip"]').tooltip({ html: true });
    };
    var init = function () {
        bindEvents();
    };
    return {
        Init: init
    };
})();