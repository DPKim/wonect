<script>
    var game_select = 0;
    var cate = <?= $get_cate ?>;

    $('.active').click(function () {

        var index = $(this).attr('data-index');

        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            game_select = 0;
        } else {
            $('.active').removeClass('selected');
            $(this).addClass('selected');
            game_select = index;
            $('.btn-next').attr('data-select', game_select);
        }
    });

    $('.btn-next1').click(function () {
        if (game_select === 0) {
            alert('Please select a game.');
        } else {
            cate = $(document).find('li.selected').attr('data-index');
            location.replace('index.php?menu=creategame&step=2&cate=' + cate + '&val=' + game_select);
        }
    });
</script>

<script>
    $('.schedule').mouseover(function () {
        var code = $(this).attr('data-code');
        $('.section-detail').css('display', 'none');
        $('.' + code).css('display', 'block');
    });
    $('.schedule').mouseout(function () {
        $('.section-detail').css('display', 'none');
    });
    $('.schedule').click(function () {
        var code = $(this).data('code');
        var games = $(this).data('games');
        var json_data = $('.div-' + code).attr('data-index');
        $('.btn-next2').attr('data-json', json_data);
    });
    $('.btn-next2').click(function () {
        var json_json = $(this).attr('data-json');
        if (!json_json) {
            alert('Please select a game.');
        } else {
            location.replace('index.php?menu=creategame&step=3&cate=' + cate + '&val=' + json_json);
        }
    });
</script>
<script>
    $('.btn_peview').click(function () {
        var name = $('#name');
        var size = $('#size');
        var fee = $('#fee');
        var multi = $('#multi');
        var prize = $('#prize');

        if (!name.val()) {
            alert('이름을 입력해 주세요.');
            name.focus();
            return false;
        } else if (name.val().length >= 200) {
            alert('허용 글자 수를 초과하였습니다');
            name.val('');
            name.focus();
            return false;
        } else if (!size.val()) {
            alert('허용 인원을 입력해 주세요.');
            size.focus();
            return false;
        } else if (!chk_isNum(size.val())) {
            alert('숫자만 입력해 주세요.');
            size.val('');
            size.focus();
            return false;
        } else if (size.val() < 2) {
            alert('최소 인원은 2인 이상입니다.');
            size.val('');
            size.focus();
            return false;
        } else if (!chk_isNum(fee.val())) {
            alert('숫자만 입력해 주세요.');
            fee.val('');
            fee.focus();
            return false;
        } else if (!fee.val()) {
            alert('금액을 입력해 주세요.');
            fee.focus();
            return false;
        } else if (fee.val() < 2) {
            alert('최소 금액은 2 골드 이상입니다.');
            fee.val('');
            fee.focus();
            return false;
        } else if (fee.val() > 1000) {
            alert('최대 금액은 1000 골드 이하입니다.');
            fee.val('');
            fee.focus();
            return false;
        }

        data = {
            'name': name.val(),
            'size': size.val(),
            'fee': fee.val(),
            'multi': multi.val(),
            'prize': prize.val()
        };
        
        $.post('ajax/preview_create.php', data, function (res) {
            $('#preview').html(res);
        });

    });
    $('.btn-next3').click(function () {
        var json_json = $('.btn-next3').attr('data-json');
        if (!json_json) {
            alert('Please select a game.');
        } else {
            location.replace('index.php?menu=creategame&step=4&cate=' + cate + '&val=' + json_json);
        }
    });
</script>

<script>
    $(document).on('click', '.btn-next4', function () {

        var size = $('#size').val();
        var fee = $('#fee').val();
        var prize = $('#prize').val();
        var name = $('#name').val().replace("'","*w*w");
        var multi = $('#multi').val();

        var data_json = $.parseJSON('<?= $get_json_data ?>');
        data_json['game'] = <?=$get_cate?>;
        data_json['size'] = size;
        data_json['fee'] = fee;
        data_json['prize'] = prize;
        data_json['name'] = name;
        data_json['multi'] = multi;
        data_json = JSON.stringify(data_json);

        location.replace('index.php?menu=creategame&step=5&cate=' + cate + '&val=' + encodeURIComponent(data_json));
    });
</script>

<script>
    $('.btn-next5').click(function () {
        var data_json = $.parseJSON('<?= $get_json_data ?>');
        $.ajax({
            url: 'ajax/creategame.php',
            type: 'post',
            data: data_json,
            beforeSend: function () {
                $('.btn-next5').attr('disabled', '');
            },
            success: function (data) {
                console.log(data);
                if (data === '100') {
                    alert('Completed');
                    location.replace('index.php?menu=lobby');
                } else {
                    alert('Error occurred');
                }
            }
        });
    });
</script>