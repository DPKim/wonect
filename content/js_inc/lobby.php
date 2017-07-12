<script src="<?= INC_PUBLIC ?>js/jquery.countdown.min.js"></script>

<script>
    var this_date;
    $(document).ready(function () {
        // 첫번째 시간탭의 날짜 가져오기
        var first_tab_date = $('.dp_lobby_group').eq(0).attr('data-date');
        this_date = first_tab_date;
        var data = {
            'category': <?= $cate ?>,
            'date': first_tab_date
        };
        post_list(data);
        lobby_container();
    });
</script>
<script>
    // 순차적으로 bg 변경하기
    function effect_chg_bg() {
        var target = $(document).find('#list_lobby > tr');
        var random1;
        var random2;
        //
        var target_length = target.length;
        //
        setInterval(function () {
            //
            random1 = Math.floor((Math.random() * target_length) + 1);
            //
            target.eq(random1).animate({
                backgroundColor: "#ffebb7",
                fontSize: "1em"
            }, 'slow');
            target.eq(random1).animate({
                backgroundColor: "#eee",
                fontSize: "12px"
            }, 'slow');
            //
        }, 2300);
        //
        setInterval(function () {
            //
            random2 = Math.floor((Math.random() * target_length));
            //
            target.eq(random2).animate({
                backgroundColor: "#ffebb7",
                fontSize: "1em"
            }, 'slow');
            target.eq(random2).animate({
                backgroundColor: "#eee",
                fontSize: "12px"
            }, 'slow');
            //
        }, 4200);
        //
        setInterval(function () {
            //
            random2 = Math.floor((Math.random() * target_length));
            //
            target.eq(random2).animate({
                backgroundColor: "#ffebb7",
                fontSize: "1em"
            }, 'slow');
            target.eq(random2).animate({
                backgroundColor: "#eee",
                fontSize: "12px"
            }, 'slow');
            //
        }, 1200);
    }

    // 로비 리스트 컨테이너 높이 변경하기
    function lobby_container() {
        var dp_lobby_tb_body = $('.dp_lobby_tb_body').position().top;
        var footer_top = $('#footer_mini').position().top;
        var diff_height = footer_top - dp_lobby_tb_body;
        $('.dp_lobby_tb_body').css('height', diff_height);
    }

    function post_list(data) {
        $.post('ajax/lobby_list.php', data, function (res) {
            $('#list_lobby').html(res);
            click_contest_list();
            click_enter_contest();
            effect_chg_bg();
        });
    }

    function click_enter_contest() {
        $('.btn-draft').click(function () {
            var game_index = $(this).attr('data-game');
            location.replace('index.php?menu=draftgame&index=' + game_index);
        });
    }

    function count_down(id, data) {
        var code_id = $(id);
        var count_down = code_id.attr(data);
        code_id.countdown(count_down, function (event) {
            if ('%D' === 1) {
                $(this).text(event.strftime('%D day %H:%M:%S'));
            } else if ('%D' > 1) {
                $(this).text(event.strftime('%D days %H:%M:%S'));
            } else {
                $(this).text(event.strftime('%H:%M:%S'));
            }
        });
    }

    function click_contest_list() {
        $('.dp_lobby_tb_content').parent('tr').hover(
                function () {
                    $(this).css('background', '#fff');
                },
                function () {
                    $(this).css('background', '#eee');
                }
        );
        $('.dp_lobby_tb_content, .btn_live').click(function () {
            var data = {
                'index': $(this).attr('data-list-index')
            };
            $.post('ajax/contest_detail_info.php', data, function (res) {
                $('.contest_detail').html(res);
                $('#draftgame').fadeIn();
                $(document).find('.btn_detail_close').click(function () {
                    $('#draftgame').fadeOut();
                });
                click_enter_contest();
                count_down('#detail_count_down', 'data-date');
                //
                var scroll_top = $(document).scrollTop();
                all_size(scroll_top);
            });
            //
            return false;
        });
    }

</script>
<script>
    $('#createGame').click(function () {
        location.replace('index.php?menu=creategame');
    });</script>
<script>
    var lobby_list = $('.dp_lobby_group');
    var sub_menu = $('#sub_menu').find('a');
    ///
    var lobby_bar = lobby_list.find('div.dp_lobby_bar');
    var lobby_bar_style = {
        'clear': 'both',
        'background-color': '#656565',
        'height': '3px'
    };
    var lobby_hover = '';
    lobby_list.css('cursor', 'pointer');
    lobby_bar.css(lobby_bar_style);
    lobby_list.mouseover(function () {
        if ($(this).hasClass('dp_lobby_group_pick') === false) {
            $(this).addClass('dp_lobby_group_active');
            $(this).find('div.dp_lobby_bar').css({
                'background-color': '#5ced04'
            });
        }
    });
    lobby_list.mouseout(function () {
        if ($(this).hasClass('dp_lobby_group_pick') === false) {
            $(this).removeClass('dp_lobby_group_active');
            $(this).find('div.dp_lobby_bar').css({
                'background-color': '#656565'
            });
        }
    });
    lobby_list.click(function () {
        var date = $(this).attr('data-date');
        this_date = date;
        var data = {
            'category': <?= $cate ?>,
            'date': date
        };
        post_list(data);
        lobby_bar.css(lobby_bar_style);
        $('div').removeClass('dp_lobby_group_pick');
        $('div').removeClass('dp_lobby_group_active');
        $(this).addClass('dp_lobby_group_pick');
        $(this).addClass('dp_lobby_group_active');
        $(this).find('div.dp_lobby_bar').css({
            'background-color': '#ff6c00'
        });
        sub_menu.removeClass('active');
        sub_menu.eq(0).addClass('active');
    });
    $('.dp_lobby_group_pick')
            .addClass('dp_lobby_group_active')
            .find('div.dp_lobby_bar').css({
        'background-color': '#ff6c00'
    });
    sub_menu.click(function () {
        sub_menu.removeClass('active');
        var this_menu = $(this).data('index');
        var data = {
            'category': <?= $cate ?>,
            'date': this_date,
            'sub_menu': this_menu
        };
        post_list(data);
        $(this).addClass('active');
    });
    var search_input = $('#search_list');
    search_input.keypress(function (key) {
        if (key.keyCode === 13) {//키가 13이면 실행 (엔터는 13)
            var search_keyword = $(this).val();
            if (search_keyword.length > 0) {
                var data = {
                    'category': <?= $cate ?>,
                    'date': this_date,
                    'search': search_keyword
                };
                post_list(data);
                $(this).val('');
                sub_menu.removeClass('active');
            }
        }
    });</script>

<script>
    click_enter_contest();
</script>