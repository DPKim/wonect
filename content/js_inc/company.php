<?php
if ($sub == 'contact') {
    ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <?php
}
?>
<script>
    var this_sub = '<?= $sub ?>';
    $('.sub_' + this_sub).addClass('active');

    $('.btn_write_notice').click(function () {
        location.replace('index.php?menu=admin');
    });

    $('.li_notice').click(function () {

        //다음 tr에 디테일이 있으면 제거
        if ($(this).next().hasClass('notice_detail')) {
            $(this).next().remove();
            return false;
        }

        var index = $(this).attr('data-index');
        var data = {
            'index': index
        };
        var next_tr = $(this);
        $.post('ajax/notice_detail.php', data, function (res) {
            next_tr.after(res);
            footer_pos();

            $('.btn_notice_detail_close').click(function () {
                $(this).parents().eq(2).remove();
            });
        });
    });

    $('#subject').on('keyup', function () {
        var len = $(this).val().length;
        $('#input_subject').text(len);
        if (len >= 200) {
            $(this).val($(this).val().substring(0, len - 1));
            alert('Limited number of characters exceeded');
        }
    });

    $('#message').on('keyup', function () {
        var len = $(this).val().length;
        $('#input_message').text(len);
        if (len >= 2000) {
            $(this).val($(this).val().substring(0, len - 1));
            alert('Limited number of characters exceeded');
        }
    });

    $('.btn_submit_contact').on('click', function () {
        var topic = $('#topic');
        var mail = $('#mail');
        var subject = $('#subject');
        var message = $('#message');

        if (mail.val() === '') {
            alert('Please enter your e-mail address');
            mail.focus();
            return false;
        } else if (subject.val() === '') {
            alert('Please enter the subject');
            subject.focus();
            return false;
        } else if (message.val() === '') {
            alert('Please enter your details');
            message.focus();
            return false;
        }

        if (chk_isMail(mail.val()) === false) {
            mail.val('');
            alert('Invalid email format.');
            mail.focus();
            return false;
        }
        if (chk_isTextNum(subject.val()) === false) {
            subject.val('');
            $('#input_subject').text(0);
            alert('Only alphanumeric characters and some special symbols can be entered.');
            subject.focus();
            return false;
        }
        if (chk_isTextNum(message.val()) === false) {
            message.val('');
            $('#input_message').text(0);
            alert('Only alphanumeric characters and some special symbols can be entered.');
            message.focus();
            return false;
        }

        if (grecaptcha.getResponse() === '') {
            alert('Please check for automatic input prevention.');
            return false;
        } else {
            var key = grecaptcha.getResponse();
        }

        var data = {
            'topic': topic.val(),
            'mail': mail.val(),
            'subject': subject.val(),
            'message': message.val(),
            'key': key
        };

        $.post('ajax/contact.php', data, function (res) {
            if (res === '500') {
                alert('Invalid access');
                grecaptcha.reset();
            } else if (res === '100') {
                alert('Completed registration');
                grecaptcha.reset();
                location.reload();
            } else {
                alert('Error occurred');
                grecaptcha.reset();
            }
        });
        return false;
    });

    // 로봇 체크
    function onloadCallback() {
        grecaptcha.render('g-recaptcha', {
            'sitekey': '6Ld-UBsUAAAAABO69G9odXya0kVtZLtglW_LlrFK'
        });
    }

    function BBS(row, paging, table) {
        var total_page;
        var total_row = row;
        var total_paging = paging;
        var tr_id = '#' + table;
        var history_tr = $(tr_id).find('tr');

        var arr_cout;
        var now_page = 0;

        // GETTER & SETTER ////////////////    
        this.get_total_row = function () {
            return total_row;
        };
        this.set_total_row = function (row) {
            return total_row = row;
        };

        this.get_total_paging = function () {
            return total_paging;
        };
        this.set_total_paging = function (paging) {
            return total_paging = paging;
        };

        this.get_tr_id = function () {
            return tr_id;
        };

        this.get_now_page = function () {
            return now_page;
        };
        this.set_now_page = function (page) {
            return now_page = page;
        };

        this.total_page = history_tr.length;
        this.arr_cout = Math.ceil(this.total_page / this.get_total_row());

        this.tr_content();
        this.page_navi();
        this.click_num();
        this.click_page_move();
    }

    BBS.prototype.tr_content = function () {
        var now_index;
        var till_index;

        if (this.get_now_page() === 0) {
            now_index = 0;
            till_index = this.get_total_row();
        } else {
            now_index = this.get_now_page() * this.get_total_row();
            till_index = (this.get_now_page() * this.get_total_row()) + this.get_total_row();
        }

        $(this.get_tr_id()).find('tr').each(function (index) {
            if (index < till_index && index >= now_index) {
                $(this).css('display', 'table-row');
            } else {
                $(this).css('display', 'none');
            }
        });
    };

    BBS.prototype.page_navi = function () {
        var navi_num = '';
        var navi_page_num = this.get_now_page();

        for (var i = 0; i < this.get_total_paging(); i++) {
            navi_page_num++;

            if (i === 0) {
                if (navi_page_num > this.get_total_paging()) {
                    navi_num += '<li class="page_move" data-num-next="' + (navi_page_num - (this.get_total_paging() + 1)) + '" style="cursor:pointer">';
                    navi_num += '<a aria-label="Previous">';
                    navi_num += '<span aria-hidden="true">&laquo;</span>';
                    navi_num += '</a>';
                    navi_num += '</li>';
                }
            }

            if (navi_page_num < this.arr_cout + 1) {
                navi_num += '<li class="paging" data-num-index="' + parseInt(navi_page_num) + '"><a style="cursor:pointer">' + parseInt(navi_page_num) + '</a></li>';
            }

            if (i === this.get_total_paging() - 1) {
                if (navi_page_num < this.arr_cout) {
                    navi_num += '<li class="page_move" data-num-next="' + (navi_page_num) + '" style="cursor:pointer">';
                    navi_num += '<a aria-label="Next">';
                    navi_num += '<span aria-hidden="true">&raquo;</span>';
                    navi_num += '</a>';
                    navi_num += '</li>';
                }
            }
        }
        $('.pagination').html(navi_num);
    };

    BBS.prototype.click_num = function () {
        var bbs_this = this;
        $('.paging').click(function () {
            $('.notice_detail').each(function () {
                $(this).remove();
            });

            var index = $(this).attr('data-num-index');
            bbs_this.set_now_page(index - 1);

            bbs_this.tr_content();
        });
    };
    BBS.prototype.click_page_move = function () {
        var bbs_this = this;
        $('.page_move').click(function () {
            $('.notice_detail').each(function () {
                $(this).remove();
            });

            var index = $(this).attr('data-num-next');
            bbs_this.set_now_page(index);

            bbs_this.tr_content();
            bbs_this.page_navi();
            bbs_this.click_num();
            bbs_this.click_page_move();
        });
    };

    new BBS(15, 10, 'table');

</script>