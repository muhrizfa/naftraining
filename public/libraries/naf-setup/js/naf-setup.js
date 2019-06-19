jQuery(document).ready(function() {

    /* init vars */
    var logbox = jQuery('.log-box').first();
    var log = jQuery('.log-box .log').first();
    var run = jQuery('#naf-run');
    var cog = run.children('.fa-cog');

    /* theme select 1st */
    jQuery('input[name="in-theme"]').first().click();

    /* wizard next/prev */
    jQuery('a.naf-prev, a.naf-next').click(function(e) {

        e.preventDefault();

        var that = jQuery(this);
        var tab = ''+that.attr('data-click');

        if (tab.length > 0) jQuery(tab + '-tab').click();

        return false;
    });

    /* log */
    function nafSetupLog(text) {

        if (text === null) {
            log.empty();
        } else {
            log.append((new Date().getTime()) + ': ' + text + '<br />');
            logbox.scrollTop(logbox[0].scrollHeight - logbox[0].clientHeight);
        }
    }

    /* gather input */
    function nafSetupGather(sco) {

        var list = {};

        nafSetupLog('Gathering inputs...');
        jQuery(sco + ' input, ' + sco + ' select').each(function(i, e) {

            var that = jQuery(this);
            var name = that.attr('name').replace('in-', '');
            var type = that.attr('type');
            var temp;

            if (type == 'checkbox') {
                if (that.prop('checked') && (that.attr('data-included') != 'yes')) {
                    temp = that.val();
                    if (typeof list[name] === 'undefined') {
                        list[name] = temp;
                    } else {
                        list[name] += (',' + temp);
                    }
                }
            } else if (type == 'radio') {
                if (that.prop('checked')) {
                    temp = that.val();
                    if (typeof list[name] === 'undefined') {
                        list[name] = temp;
                    } else {
                        if (list[name].length <= 0) list[name] = temp;
                    }
                }
            } else {
                list[name] = that.val();
            }
            nafSetupLog(name + ': ' + list[name]);

        });

        return list;
    }

    function nafSetupState(flag) {

        if (flag) {
            cog.show();
            run.attr('disabled', 'disabled').prop('disabled', true);
        } else {
            cog.hide();
            run.removeAttr('disabled').prop('disabled', false);
        }
    }

    function nafSetupRun(baseUrl, inputs) {

        nafSetupLog('OK, running configurator, please wait...');
        nafSetupState(true);

        jQuery.ajax({
            url: baseUrl + '/setup/run',
            method: 'POST',
            data: {
                guid: (new Date().getTime()),
                code: 0,
                data: JSON.stringify(inputs)
            },
            dataType: 'json',
            success: function(r) {

                if (r.code == 0) {
                    nafSetupLog('OK, done! Will be redirecting in a few seconds...');
                    setTimeout(function() { window.location = baseUrl + '/setup/finish'; /* baseUrl + '/' + r.data */ }, 2000);
                } else {
                    nafSetupLog('Oops, unfortunately something was wrong!');
                    nafSetupLog(r.info);
                    nafSetupLog('You might want to review you choices before trying again...');
                    nafSetupState(false);
                }
            },
            error: function(x, s, e) {

                nafSetupLog('Oops, an error has occured, configuration failed!');
                nafSetupLog(s + ', ' + e);
                nafSetupState(false);
            }
        });
    }

    /* run setup */
    run.click(function(e) {

        e.preventDefault();

        nafSetupLog(null);
        nafSetupRun(jQuery('meta[name="home"]').first().attr('content'), nafSetupGather('#in-config'));

        return false;
    });
});