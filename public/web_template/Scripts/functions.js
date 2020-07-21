function LoadGoogleMap(dropPointId, $divResult) {
    $.ajax({
        url: "/Home/LoadGoogleMap",
        data: { dropPointId },
        async :false,
        success: function (data) {
            $divResult.html(data);
        }
    });
}