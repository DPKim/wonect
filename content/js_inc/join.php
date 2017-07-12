<script>
    $('html, body').css('background', 'url(<?= INC_PUBLIC ?>css/images/dark.png)');

    function join_process() {

        var chkbox = $('input:checkbox[name="chkbox"]');
        var is_chk = true;
        chkbox.each(function () {
            if (this.checked === false) {
                alert('Please check all the checkboxes.');
                is_chk = false;
                return false;
            }
        });

        if (is_chk === false) {
            return false;
        }

        var id = $('#id');
        var name = $('#name');
        var pw = $('#pw');
        var co_pw = $('#co_pw');
        var timezone = $('#timezone');
        var day = $('#day');
        var month = $('#month');
        var year = $('#year');

        if (!id.val()) {
            alert('Please enter your ID.');
            id.focus();
            return false;
        } else if (chk_isMail(id.val()) === false) {
            alert('Please enter your e-mail address');
            id.val('');
            id.focus();
            return false;
        } else if (!name.val()) {
            alert('Please, Input your name.');
            name.focus();
            return false;
        } else if (chk_isTextNum_join(name.val()) === false) {
            alert('Only alphanumeric characters and some special symbols can be entered.');
            name.val('');
            name.focus();
            return false;
        } else if (!pw.val()) {
            alert('Please enter a password.');
            pw.focus();
            return false;
        } else if (pw.val().length < 8) {
            alert('At least 8 characters');
            pw.val('');
            pw.focus();
            return false;
        } else if (chk_isTextNum_join(pw.val()) === false) {
            alert('Only alphanumeric characters and some special symbols can be entered.');
            pw.val('');
            pw.focus();
            return false;
        } else if (!co_pw.val()) {
            alert('Please enter your password verification.');
            co_pw.focus();
            return false;
        } else if (pw.val() !== co_pw.val()) {
            alert('Passwords do not match.');
            co_pw.val('');
            co_pw.focus();
            return false;
        } else if (!day.val()) {
            alert('Please enter day of birth.');
            day.focus();
            return false;
        } else if (!month.val()) {
            alert('Please enter your month of birth.');
            month.focus();
            return false;
        } else if (!year.val()) {
            alert('Please enter the year of birth.');
            year.focus();
            return false;
        }

        var data = {
            'id': id.val(),
            'name': name.val(),
            'pw': pw.val(),
            'timezone': timezone.val(),
            'day': day.val(),
            'mon': month.val(),
            'year': year.val()
        };

        $.ajax({
            type: 'post',
            url: 'ajax/memberjoin.php',
            data: data,
            beforeSend: function () {
                $('.btn-join-member').attr('disabled', '');
            },
            success: function (data) {
//                console.log(data);
                if (data === '100') {
                    alert('Completed registration');
                    location.replace('index.php?menu=lobby');
                    return;
                } else if (data === '501') {
                    alert('Please enter your ID in the email you are using');
                    id.focus();
                    $('.btn-join-member').removeAttr('disabled');
                    return;
                } else {
                    console.log(data);
//                    alert('Error occurred');
                    $('.btn-join-member').removeAttr('disabled');
                    return;
                }
            }
        });
    }
</script>

<script>
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
                console.log(data);
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