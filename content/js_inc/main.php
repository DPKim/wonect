<script>
    function join_process() {

        var chkbox = $('input:checkbox[name="chkbox"]');
        var is_chk = true;
        chkbox.each(function () {
            if (this.checked === false) {
                alert('모든 체크박스에 체크를 해 주세요');
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
            alert('ID를 입력해 주세요.');
            id.focus();
            return false;
        } else if (!name.val()) {
            alert('이름을 입력해 주세요.');
            name.focus();
            return false;
        } else if (!pw.val()) {
            alert('비밀번호를 입력해 주세요.');
            pw.focus();
            return false;
        } else if (!co_pw.val()) {
            alert('비밀번호 확인을 입력해 주세요.');
            co_pw.focus();
            return false;
        } else if (!day.val()) {
            alert('날짜를 입력해 주세요.');
            day.focus();
            return false;
        } else if (!month.val()) {
            alert('월을 입력해 주세요.');
            month.focus();
            return false;
        } else if (!year.val()) {
            alert('태어날 해를 입력해 주세요.');
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
                    alert('가입 완료');
                    location.reload();
                    return;
                } else if (data === '501') {
                    alert('ID는 사용 중인 이메일로 입력해 주세요');
                    id.focus();
                    $('.btn-join-member').removeAttr('disabled');
                    return;
                } else {
                    alert('에러 발생');
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
            alert('ID를 입력해 주세요.');
            id.focus();
            return false;
        } else if (!pw.val()) {
            alert('비밀번호를 입력해 주세요.');
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
                    alert('에러 발생');
                    $('.btn-login').removeAttr('disabled');
                    return;
                }
            }
        });
    }
</script>