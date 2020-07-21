var App = function() {
    return {
        // wrapper function to  block element(indicate loading)
        blockUI: function(el, centerY) {
            var el = jQuery(el);
            if (el.height() <= 400) {
                centerY = true;
            }
            el.block({
                message: '<img src="/images/icon/ajax-loading.gif" align="">',
                centerY: centerY != undefined ? centerY : true,
                css: {
                    top: '10%',
                    border: 'none',
                    padding: '2px',
                    backgroundColor: 'none'
                },
                overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.05,
                    cursor: 'wait'
                }
            });
        },

        // wrapper function to  un-block element(finish loading)
        unblockUI: function(el) {
            jQuery(el).unblock({
                onUnblock: function() {
                    jQuery(el).removeAttr("style");
                }
            });
        }
    };
}();
var AppMobile = function () {
    return {
        // wrapper function to  block element(indicate loading)
        blockUI: function (el, centerY) {
            var el = jQuery(el);
            if (el.height() <= 400) {
                centerY = true;
            }
            el.block({
                message:null,
                centerY: centerY != undefined ? centerY : true,
                css: {
                    top: '10%',
                    border: 'none',
                    padding: '2px',
                    backgroundColor: 'none'
                },
                overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.6,
                    cursor: 'no-drop'
                }
            });
        },

        // wrapper function to  un-block element(finish loading)
        unblockUI: function (el) {
            jQuery(el).unblock({
                onUnblock: function () {
                    jQuery(el).removeAttr("style");
                }
            });
        }
    };
}();
var Alert = function () {
    return {
        Message: function (message) {
            swal(message);
        },
        Success: function (message) {
            swal(message, "", "success");
        },
        Error: function (message) {
            swal(message, "", "error");
        },
        Warning: function (message) {
            swal(message, "", "warning");
        }
    };
}();