jQuery(document).ready(function() {

    var pbar = jQuery('#install-bar');
    var ptxt = jQuery('#install-info');

    /* calc total modules */
    var index = 0;
    var count = 0;
    var mlist = [];
    var mcalc = 0;
    for (var i in modules) {
      count++;
      mlist[count] = i;
    }

    /* reload page after install */
    function nafPrimitiveReload() {

        pbar.attr('aria-valuenow', 100).css({'width': '100%'}).children('span').text('100% Complete');
        ptxt.addClass('success').text('Done! Loading setup...');
        setTimeout(function() {

            window.location.reload(true);
        }, 250);
    }

    /* install module */
    function nafPrimitiveInstall() {

        /* set progress */
        index++;
        mcalc = Math.round((index / count) * 100);
        pbar.attr('aria-valuenow', mcalc).css({'width': mcalc + '%'}).children('span').text(mcalc + '% Complete');
        ptxt.html('Installing module <strong>' + mlist[index] + '</strong>');

        /* call install */
        jQuery.ajax({
            "url": install,
            "method": "post",
            "data": {
                "module" : mlist[index],
            },
            "dataType": "json",
            "success": function(d, s, x) {

                /* ok? */
                if (d.code == '0') {

                    /* success? continue install */
                    if (index < count) {
                        setTimeout(nafPrimitiveInstall, 100);
                    } else {
                        nafPrimitiveReload();
                    }

                } else {

                    /* error, stop install */
                    ptxt.addClass('error').html('Error! Unable to install module <strong>' + d.module + '</strong>, refresh this page to try again!');

                }
            },
            "error": function(x, s, e) {

            }
        });
    }

    /* do install? */
    if (count > 0) {
        nafPrimitiveInstall();
    } else {
        nafPrimitiveReload();
    }
});