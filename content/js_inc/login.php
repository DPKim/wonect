<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script>
    $('html, body').css('background', 'url(<?= INC_PUBLIC ?>css/images/dark.png)');

    // 로봇 체크
    function onloadCallback() {
        grecaptcha.render('g-recaptcha', {
            'sitekey': '6Ld-UBsUAAAAABO69G9odXya0kVtZLtglW_LlrFK'
        });
    }

    function chg_resetPw() {
        $('.modal-title').text('RESET PASSWORD');
        $('.comment').text('We will send you an email containing a link to complete this process and reset your password.');
        $('.small_title').text('Input your code');
        $('.input_form').html('<input id="code" type="text" class="form-control" placeholder="Code">');
        $('.btn_submit').html('<button type="button" class="btn btn-primary btn_chk_code">Submit</button>');
    }

    function login_process() {
        var id = $('#id_login');
        var pw = $('#pw_login');

        if (!id.val()) {
            alert('Please enter your ID.');
            id.focus();
            return false;
        } else if (!pw.val()) {
            alert('Please enter a password.');
            pw.focus();
            return false;
        }

        var data = {
            'id': id.val(),
            'pw': pw.val()
        };

        $.ajax({
            type: 'post',
            url: 'ajax/memberlogin.php',
            data: data,
            beforeSend: function () {
                $('.btn-login').attr('disabled', '');
            },
            success: function (data) {
                if (data === '100') {
                    location.reload();
                    return;
                } else {
                    alert('Error occurred');
                    $('.btn-login').removeAttr('disabled');
                    return;
                }
            }
        });
    }
</script>
<script>
    $(document).ready(function () {
        $('.btn_find_pw').click(function () {
            var mail = $('#email');

            if (mail.val() === '') {
                alert('Please enter your e-mail address');
                mail.focus();
                return false;
            }

            if (chk_isMail(mail.val()) === false) {
                mail.val('');
                alert('Invalid email format.');
                mail.focus();
                return false;
            }

            if (grecaptcha.getResponse() === '') {
                alert('Please check for automatic input prevention.');
                return false;
            } else {
                var key = grecaptcha.getResponse();
            }

            var data = {
                'mail': mail.val(),
                'key': key
            };

            $.ajax({
                url: 'ajax/find_pw.php',
                type: 'post',
                data: data,
                success: function (res) {
                    var json_res = $.parseJSON(res);

                    if (json_res.result === 500) {
                        alert('Invalid access');
                        grecaptcha.reset();
                    } else if (json_res.result === 100) {
                        grecaptcha.reset();
                        alert('Check your e-mail!');
                        location.reload();
                    } else if (json_res.result === 501) {
                        $('#msg').text('* Please enter a valid email address');
                        grecaptcha.reset();
                        mail.val('');
                        mail.focus();
                    } else {
                        alert('Error occurred');
                        grecaptcha.reset();
                    }
                }
            });
            return false;
        });
    });
</script>
<script>
    // 페이스북 로그인
    $(document).ready(function () {
//        $.ajaxSetup({cache: true});
        $('#login_facebook').click(function () {
            $.getScript('//connect.facebook.net/en_US/sdk.js', function () {
                FB.init({
                    appId: '1888149218130521',
                    version: 'v2.8'
                });
                FB.getLoginStatus(function (response) {
                    if (response.status !== 'connected') {
                        FB.login(function (response) {
                            if (response.status === 'connected') {
                                FB.api('/me', {fields: 'id, email, name, age_range, birthday, gender, languages, picture, locale, timezone'}, function (response) {
                                    var data = {
                                        'id': response.id,
                                        'mail': response.email,
                                        'name': response.name,
                                        'gender': response.gender,
                                        'languages': response.languages,
                                        'picture': response.picture.data.url,
                                        'locale': response.locale,
                                        'timezone': response.timezone
                                    };
                                    $.post('ajax/facebook_login.php', data, function (res) {
                                        if (res === '100' || res === '101') {                                           
                                            location.replace('index.php?menu=lobby');
                                        } else if(res === '600') {
                                            alert('Already registered ID(e-mail) exists.')
                                        } else {
                                            alert('Error occurred');
                                        }
                                    });
                                });
                            } else {
                                alert('Facebook login failed.');
                            }

                        }, {scope: 'public_profile, email'});
                    } else {
                        FB.logout(function (response) {
                            location.replace('index.php?menu=lobby');
                        });
                    }
                });
            });
        });
    });
</script>