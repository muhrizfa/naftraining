(function ($) {

    $.fn.neuronAlert = function(msg, type) {

        /* no element, get default placeholder */
        var that = this;
        if (this.length == 0) {
            that = window.jQuery('.alert-placeholder').first();
        }

        /* set alert class */
        if (that.length > 0) {
            var temp = 'alert-danger';
            if (type == 'warning') {
                temp = 'alert-warning';
            } else if (type == 'info') {
                temp = 'alert-info';
            } else if (type == 'success') {
                temp = 'alert-success';
            }

            /* alert */
            that.html('<div role="alert" class="alert ' + temp + '"><button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>' + msg + '</div>');
        }

        return this;
    };

}(jQuery));