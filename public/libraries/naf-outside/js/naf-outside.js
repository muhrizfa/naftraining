jQuery(document).ready(function() {

    /* home url */
    var home = jQuery('meta[name="homeurl"]').attr('content');
    if (home == undefined) home = window.location.href;

    /* login logic */
    var login = jQuery('form#naf-login').first();
    if (login.length > 0) {

        /* click on login */
        login.find('#naf-login-do').click(function(e) {

            var user = login.find('#naf-login-user').val();
            var pass = login.find('#naf-login-pass').val();

            jQuery.ajax({
                url: home + '/composite/user/authenticate',
                type: 'post',
                data: {
                    'code' : 0,
                    'data' : JSON.stringify({'code': user, 'password': pass})
                },
                dataType: 'json',
                success: function(r) {

                    if (r.code == 0) {
                        window.location.reload();
                    } else {
                        alert(r.info);
                    }
                }
            });

        });
    }
});