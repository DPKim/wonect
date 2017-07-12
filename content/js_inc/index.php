<script>
    $(document).ready(function () {

        var get_menu = '<?= $get_menu ?>';
        if (get_menu === 'join' || get_menu === 'login' || get_menu === 'confirm' || get_menu === 'events' || get_menu === 'purchase') {
            $('html, body').css('background', 'url(<?= INC_PUBLIC ?>css/images/dark.png)');
        } else {
            $('html, body').css({
                'position': 'relative',
                'background-image': 'url("<?= INC_PUBLIC ?>css/bg_mlb.png")',
                'background-repeat': 'no-repeat',
                'background-position': 'center top'
//                'background-size': 'cover'
//                'box-shadow' : '0px 5px 10px #000 inset'
            });
            $('.navibar').css({
                'box-shadow': '0px 2px 10px #000'
            });
            $('.top_banner').css({
                'box-shadow': '0px 2px 10px #000'
            });
        }

        // 로딩 메뉴에 따른 페이지 변경
        $('.navbar-nav > li').each(function () {
            if ($(this).find('a').text().toLowerCase() === '<?= $get_menu ?>') {
                $(this).addClass('active');
            }
        });

        // 메뉴 클릭 시 CSS 변경
        $('.nav_menu > ul > li').click(function () {
            var menu = $(this).find('a').text().toLowerCase();
            if ($(this).hasClass('notyet') === false) {
                location.replace('index.php?menu=' + menu);
            }
        });

        // 메뉴 상단 버튼들 클릭 시
        $('.btn_go_login').click(function () {
            location.replace('index.php?menu=login');
        });
        $('.btn_go_join').click(function () {
            location.replace('index.php?menu=join');
        });
        $('.buy_gold').click(function () {
            location.replace('index.php?menu=store');
        });
        $('#logout').click(function () {
            location.replace('component/logout.php');
        });


        $(document).on('click', '.notyet', function () {
            alert('This feature is currently under construction');
        });


        $('.btn_footer_in').css('cursor', 'pointer');
        $('.btn_footer_out').css('cursor', 'pointer');

        $('.btn_footer_out').click(function () {
            $(document).find('#footer').toggle();
            $(document).find('#footer_mini').show();
        });

        $('.btn_footer_in').click(function () {
            $(document).find('#footer_mini').toggle();
            $(document).find('#footer').show();
            var position = $(document).find('#footer').offset();
            $('html, body').animate({scrollTop: position.top}, 100);
        });

        // 현재 메뉴에 on
        var this_page = '<?= $get_menu ?>';
        $('.this_menu').filter(function () {
            return $(this).attr('data-index') === this_page;
        }).addClass('on');

        // 언어 지역 세팅
        $('.btn_ls').click(function () {
            $('.layer_ls').toggle();
        });

        $('.btn_ls_close').click(function () {
            $('.layer_ls').toggle();
        });

        $('footer').load(footer_pos());

        $(window).resize(function () {
            footer_pos();
        });

        //언어 처리
        var my_language = '<?= $language ?>';

        //타임존 처리
        var timezone = $('#timezone');
        var timezone_option = timezone.find('option');
        var my_timezone = '<?= $locale ?>';

        timezone_option.filter(function () {
            return $(this).val() === my_timezone;
        }).attr('selected', 'selected');
        timezone.on('change', function () {
            my_timezone = timezone.find('option:selected').val();
        });

        $('.btn_ls_apply').on('click', function () {
            var data = {
                'language': my_language,
                'timezone': my_timezone
            };
            $.post('ajax/set_locale.php', data, function (res) {
                if (res === '100') {
                    location.reload();
                } else {
                    alert('Error occurred');
                }
            });
        });

        // 라인업 클릭 시 처리
        $(document).on('click', '.player_name', function () {
            var index = $(this).data('index');
            var category = $(this).data('category');
            //
            var data = {
                'index': index,
                'category': category
            };
            $.post('ajax/player_detail.php', data, function (res) {
                var detatil = $(document).find('#player_detail');
                detatil.find('div.player_detail').html(res);
                detatil.toggle();
                initi_load_player_detail();
            });
        });

        function initi_load_player_detail() {

            var scroll_top = $(this).scrollTop();
            all_size(scroll_top);

            $('.btn_detail_close').click(function () {
                $(this).parents('#player_detail').toggle();
            });
        }


    });

    function addZero(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }

    function differTime(timeA, timeB) {
        var start_actual_time = timeA;
        var end_actual_time = timeB;

        start_actual_time = new Date(start_actual_time);
        end_actual_time = new Date(end_actual_time);

        var diff = end_actual_time - start_actual_time;

        var diffSeconds = diff / 1000;
        var M = Math.floor(diffSeconds / 60);
        var HH = Math.floor(diffSeconds / 3600);
        var DD = Math.floor(HH / 24);
        var MM = Math.floor(DD / 30);

        return {
            'month': MM,
            'day': DD,
            'hour': HH,
            'min': M
        };
    }

    // 숫자, 영문, 일부 특수기호만
    function chk_isTextNum_join(strValue)
    {
        var strReg = /^[A-Za-z0-9!@#$%^&*]+$/gi;

        if (!strReg.test(strValue))
        {
            return false;
        } else {
            return true;
        }
    }

    // 오직 숫자
    function chk_isNum(numValue)
    {
        var strReg = /^[0-9]+$/gi;

        if (!strReg.test(numValue))
        {
            return false;
        } else {
            return true;
        }
    }
    function chk_isTextNum(strValue)
    {
        var strReg = /^[A-Za-z0-9!@#$%^&*~?!.,\'\"\n ]+$/gi;

        if (!strReg.test(strValue))
        {
            return false;
        } else {
            return true;
        }
    }

    // 이메일 체크
    function chk_isMail(email) {
        var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

        if (regex.test(email) === false) {
            return false;
        } else {
            return true;
        }
    }

    var size = [];

    // 별도창 제어
    function get_size() {
        size.width = $(window).width();
        size.windowHeight = $(window).height();

        // 푸터 높이 계산해 넣기
        var footer_height = 0;
        if ($('#footer_mini').css('display') === 'block') {
            footer_height = parseInt($('#footer_mini').css('height'));
        } else if ($('#footer').css('display') === 'block') {
            footer_height = parseInt($('#footer').css('height'));
        }

        if (size.windowHeight >= $('#cal_content').position().top + footer_height) {
            size.height = size.windowHeight;
        } else {
            size.height = $('#cal_content').position().top + footer_height;
        }

        size.windowHeight = $(window).height();
        return size;
    }

    // 별도창 전체 크기 & 화면 중앙
    function all_size(scroll_top) {
        var width = size.width;
        var height = size.height;
        //
        var windowHeight = size.windowHeight + scroll_top * 2;
        //
        var containerWidth = $(".lb_container").width();
        var containerHeight = $(".lb_container").height();
        //
        var leftMargin = (width - containerWidth) / 2;
        var heightMargin = ((windowHeight - containerHeight) / 2);

        $(".light_room").css({
            'width': width,
            'height': height
        });

        $(".lb_container").css({
            'margin-left': leftMargin,
            'margin-top': heightMargin
        });
    }

    $(document).on('click', function () {
        footer_pos();
    });

    //푸터 제어
<?php
if ($get_menu == 'login' || $get_menu == 'join' || $get_menu == 'admin') {
    ?>
        function footer_pos() {
        }
    <?php
} else {
    ?>
        function footer_pos() {
            var window_height = $(window).height();
            var content_height = $('#cal_content').offset().top;
            var sub_height = window_height - content_height;

            if (sub_height < 60) {
                $('#footer').css('position', 'relative');
                $('#footer_mini').css('position', 'relative');
            } else {
                $('#footer').css('position', 'absolute');
                $('#footer_mini').css('position', 'absolute');
            }
            get_size();
        }
    <?php
}
?>
</script>