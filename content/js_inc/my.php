<script src="<?= INC_PUBLIC ?>js/jquery.form.js"></script>
<script>

    $(document).ready(function () {
        var this_sub = '<?= $sub ?>';

        $('.sub_' + this_sub).addClass('active');

        $('#upload_form').ajaxForm({
            dataType: 'json',
            success: function (data) {
                if (data['status'] === '100') {
                    $('#upload').modal('hide');
                    location.reload();
                } else {
                    alert(data['message']);
                }
            }
        });

        $('.li_ticket').click(function () {

            //다음 tr에 디테일이 있으면 제거
            if ($(this).next().hasClass('ticket_detail')) {
                $(this).next().remove();
                return false;
            }

            var index = $(this).attr('data-index');
            var data = {
                'index': index
            };
            var next_tr = $(this);
            $.post('ajax/ticket_detail.php', data, function (res) {
                next_tr.after(res);
                footer_pos();

                $('.btn_ticket_detail_close').click(function () {
                    $(this).parents().eq(2).remove();
                });
            });
        });

        $('.btn_send_ticket').click(function () {
            location.replace('index.php?menu=company&sub=contact');
        });

        $('.btn_chg_pw').click(function () {
            var pw = $('#now_pw').val();
            if (!pw) {
                alert('Please enter a password.');
                $('#now_pw').focus();
                return false;
            } else {
                var data = {
                    'pw': pw
                };
            }
            $.post('ajax/chk_pw.php', data, function (res) {
                if (res === '500') {
                    alert('Please enter your current password correctly.');
                    $('#now_pw').val('');
                    $('#now_pw').focus();
                } else {
                    $('#chg_pw_content').html(res);

                    $('.btn_new_pw').click(function () {
                        var new_pw = $('#new_pw');
                        var cf_new_pw = $('#cf_new_pw');

                        if (!new_pw.val()) {
                            alert('Please enter a password.');
                            new_pw.focus();
                            return false;
                        } else if (!cf_new_pw.val()) {
                            alert('Please enter your password verification.');
                            cf_new_pw.focus();
                            return false;
                        } else if (new_pw.val() !== cf_new_pw.val()) {
                            alert('Passwords do not match.');
                            new_pw.val('');
                            cf_new_pw.val('');
                            new_pw.focus();
                            return false;
                        }
                        data = {
                            'new_pw': new_pw.val(),
                            'cf_new_pw': cf_new_pw.val()
                        };
                        $.post('ajax/chg_pw.php', data, function (res) {
                            if (res === '100') {
                                new_pw.val('');
                                cf_new_pw.val('');
                                $('#chgPw').modal('hide');
                                alert('Your password has been changed.');
                                return false;
                            } else {
                                alert('Error occurred');
                                new_pw.val('');
                                cf_new_pw.val('');
                                new_pw.focus();
                                return false;
                            }
                        });
                    });
                }
            });
        });

        btn_bitcoin();

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

        function btn_bitcoin() {
            var input = $('#bitcoin');
            $(document).on('click', '.btn_edit_bitcoin', function () {
                var code = input.val();
                if (!code) {
                    alert('Input your bit-coin address');
                    input.focus();
                    return false;
                } else {
                    var data = {
                        'code': code
                    };
                }
                $.post('ajax/edit_bitcoin.php', data, function (res) {
                    if (res === '501') {
                        alert('Invalid address. Please check address.');
                        input.focus();
                        return false;
                    } else if (res === '100') {
                        alert('Complete');
                        return;
                    } else if (res === '400') {
                        alert('It is already registered. Please check your address');
                        input.focus();
                        return false;
                    } else {
                        alert('Error occurred.');
                        input.focus();
                        return false;
                    }
                });
            });
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
                $('.ticket_detail').each(function () {
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
                $('.ticket_detail').each(function () {
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
    });
</script>