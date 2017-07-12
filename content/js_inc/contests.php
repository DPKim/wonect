<script src="<?= INC_PUBLIC ?>js/jquery.countdown.min.js"></script>
<script src="<?= INC_PUBLIC ?>js/jquery.number.min.js"></script>

<script>
    $(document).ready(function () {
        var this_tab = '<?= $sub ?>';
        var this_cate = 0;
        var tab_contest = $('#tab_contest > li');

        tab_contest.click(function () {
            var trim_tab = $.trim($(this).text().toLowerCase());
            location.replace('index.php?menu=contests&sub=' + trim_tab);
        });

        tab_contest.filter(function () {
            return $.trim($(this).text().toLowerCase()) === this_tab;
        }).addClass('checked');

        autoProccess();

        $('.ul_game_cate > li').click(function () {
            $('.ul_game_cate > li').removeClass('checked');
            $(this).addClass('checked');

            var new_cate = $(this).attr('data-index');
            set_cate(new_cate);

            autoProccess();
        });

        function set_cate(cate) {
            return this_cate = cate;
        }

        function postAjax(index, page) {
            var data = {
                'category': index,
                'page': page
            };
            $.post('ajax/contests_' + this_tab + '.php', data, function (data) {
                $('#body_page').html(data);
//                var total_winning = $.number($('#total_winning').val(), 2);
                $('#t_winning').text($('#total_winning').val());
                afterAjax();
            });
        }

        function naviClick(this_cate) {
            $(document).on('click', '.paging', function () {
                var page = $(this).attr('data-num-index') - 1;
                postAjax(this_cate, page);
            });
        }

        function moveClick(this_cate) {
            $(document).on('click', '.page_move', function () {
                var page = $(this).attr('data-num-next') - 1;
                postAjax(this_cate, page);
            });
        }

        function autoProccess() {
            postAjax(this_cate, 0);
            naviClick(this_cate);
            moveClick(this_cate);
            contest_detail();
        }

        // 기본  body box 크리 제어
        function box_350() {
            var temp_h = $('.lu_tb').height();
            if (temp_h < 350) {
                $('.lu_tb').css('height', '350px');
            }
        }


        // 게임 정보 상세 보기
        function contest_detail() {
            $(document).on('click', '.content_BBS', function () {
                var scroll_top = $(this).scrollTop();
                var data = {
                    'index': $(this).attr('data-idx')
                };
                $.post('ajax/contest_detail_info.php', data, function (res) {
                    $('.contest_detail').html(res);
                    $('#draftgame').fadeIn();
                    $(document).find('.btn_detail_close').click(function () {
                        $('#draftgame').fadeOut();
                    });
                    click_enter_contest();
                    all_size(scroll_top);
                    count_down('#detail_count_down', 'data-date');
                });
            });

            function click_enter_contest() {
<?php
if ($sub == 'upcoming') {
    ?>
                    $('.btn-draft').click(function () {
                        var game_index = $(this).attr('data-game');
                        location.replace('index.php?menu=draftgame&index=' + game_index);
                    });
    <?php
} else {
    ?>
                    $('.btn-draft').remove();
    <?php
}
?>
            }

            function count_down(id, data) {
                var code_id = $(id);
<?php
if ($sub == 'upcoming') {
    ?>
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
    <?php
} else {
    ?>
                    code_id.text('<?= strtoupper($sub) ?>');
    <?php
}
?>


            }
        }

        function afterAjax() {
            box_350();

            // 게시물 타이틀 롤오버 이벤트
            $(".title_marquee").mouseenter(function () {
                var text = $(this).text();
                if ($(this).hasClass('over') === false) {
                    $(this).addClass('over');
                    $(this).html('<marquee behavior="alternate" scrollamount="2" class="contest_name title_marquee" >' + text + '</marquee>');
                }
            });
            $(".title_marquee").mouseleave(function () {
                $(this).removeClass('over');
                var text = $(this).text();
                $(this).html('<span class="contest_name title_marquee" >' + text + '</span>');
            });

            // 새로운 컨테스트
            $('.btn_add_contest').click(function () {
                var url = $(this).attr('data-index');
                location.replace('index.php?menu=draftgame&index=' + url);
                return false;
            });

            //컨테스트 upcoming edit 버튼 클릭 시 이벤트        
            $('.btn_contest_edit').click(function () {
                var index = $(this).attr('data-index');
                var data = {
                    'index': index
                };
                var target_lineups = $(this).parent('td').parent('tr');
                var target_button = $(target_lineups).next().children('td').eq(0).children('div').eq(1).children('button');

                // 기존 테이블의 높이 계산
                $('.lu_tb').css('height', 'auto');

                if ($(target_button).attr('data-index') !== index) {

                    $.get('ajax/contests_chk_upcoming_edit.php', data, function (res) {
                        $(target_lineups).after(res);

                        $('.btn_close').click(function () {
                            var target = $(this).parent().parent().parent();
                            var index = $(this).attr('data-index');

                            $(target).remove();
                            $('button[data-index="' + index + '"]').eq(0).on('click');

                            // 줄일 때는 div 높이 값의 설정을 변경해 줌 (nav의 위치가 이상해지는 문제를 위한 해결)
                            box_350();
                        });

                        $('.btn_edit_lineup').click(function () {
                            var lineup_index = $(this).attr('data-index-lineup');
                            location.replace("index.php?menu=draftgame&index=" + index + "&type=1&lu=" + lineup_index);
                        });
                    });
                }
            });

            //컨테스트 history result 버튼 클릭 시 이벤트        
            $('.btn_contest_result').click(function () {
                var index = $(this).attr('data-index');
                var data = {
                    'index': index
                };
                var target_lineups = $(this).parent('td').parent('tr');
                var target_button = $(target_lineups).next().children('td').eq(0).children('div').eq(1).children('button');


                // 기존 테이블의 높이 계산
                $('.lu_tb').css('height', 'auto');

                if ($(target_button).attr('data-index') !== index) {

                    $.get('ajax/contests_chk_history_result.php', data, function (res) {
                        $(target_lineups).after(res);

                        $('.btn_close').click(function () {
                            var target = $(this).parent().parent().parent();
                            var index = $(this).attr('data-index');

                            $(target).remove();
                            $('button[data-index="' + index + '"]').eq(0).on('click');

                            // 줄일 때는 div 높이 값의 설정을 변경해 줌 (nav의 위치가 이상해지는 문제를 위한 해결)
                            box_350();
                        });

                        $('.btn_edit_lineup').click(function () {
                            var lineup_index = $(this).attr('data-index-lineup');
                            location.replace("index.php?menu=draftgame&index=" + index + "&type=1&lu=" + lineup_index);
                        });
                    });
                }
            });
        }
    });
</script>