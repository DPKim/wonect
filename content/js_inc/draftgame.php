<script src="<?= INC_PUBLIC ?>js/jquery.countdown.min.js"></script>
<script src="<?= INC_PUBLIC ?>js/jquery.number.min.js"></script>

<script type="text/javascript">
    var count_time = $("#game_date").attr('data-next-game');
    $("#game_date").countdown(count_time, function (event) {
        $(this).text(event.strftime('%D days %H:%M:%S'));
    });</script>
<script>
    $(document).ready(function () {

<?php
if ($lu_idx) {
    ?>
            var data = {
                'index': <?= $g_idx ?>,
                'type': <?= $type ?>,
                'lu': <?= $lu_idx ?>
            };
    <?php
} else {
    ?>
            var data = {
                'index': <?= $g_idx ?>,
                'type': <?= $type ?>
            };
    <?php
}
?>
        $.post('ajax/player_list.php', data, function (res) {
            var json = $.parseJSON(res);
            var tr = '';
            $.each(json, function (index, obj) {
                if (index === 0) {
//                    console.log(obj);
                }
                tr += make_tr(json[index]);
            });
            $('#player_list').html(tr);
            init_add_player();
        });
        $('.tooltip_cp').tooltip();
    });</script>
<script>

    function make_tr(json) {
        var tr = '';
        var flex = '';
        var name;
        var class_flex;
        //
        if (json[7] !== 'TEAM') {
            class_flex = 'pos_FLEX';
            flex = 'data-flex="' + json[7] + '"';
        }
        //
        tr = '<tr class="pos_' + json[0] + ' ' + class_flex + '">';
        tr += '<td style="width:10%">' + json[1] + '</td>';
        tr += '<td style="width:25%; cursor:pointer" class="player_name" data-category="' + json[15] + '" data-index="' + json[14] + '">';
        if (json[15] === 'mlb') {
            tr += json[2] + ' ' + json[3];
            name = json[11] + ' ' + json[12];
        } else if (json[15] === 'lol') {
            tr += json[3];
            name = json[12];
        }
        //
        tr += '</td>';
        tr += '<td style="width:20%">' + json[4] + '</td>';
        tr += '<td style="width:10%">' + json[5] + '</td>';
        tr += '<td style="width:10%">' + json[5] + '</td>';
        tr += '<td style="width:6%; text-align:right">$</td>';
        tr += '<td style="width:9%;" align=left>' + json[6];
        tr += '<td style = "width:13%" >';
        tr += '<img class="add_player" ';
        tr += 'data-pos="' + json[7] + '" ';
        tr += flex;
        tr += 'data-game="' + json[8] + '" ';
        tr += 'data-index="' + json[9] + '" ';
        tr += 'data-salary="' + json[10] + '" ';
        tr += 'data-name ="' + name + '" ';
        tr += 'data-team ="' + json[13] + '" ';
        tr += 'src="<?= INC_PUBLIC ?>images/plus.png">';
        tr += '</td>';
        tr += '</tr>';
        return tr;
    }

    function sort(pos) {
        var table = $('.pick_player');
        if (pos === 'All') {
            table.each(function () {
                table.find('tr').show();
            });
        } else if (pos === 'FLEX') {
            table.find('tr').hide();
            table.each(function () {
                table.find('.pos_' + pos).show();
            });
        } else {
            table.find('tr').hide();
            table.each(function () {
                table.find('.pos_' + pos).show();
            });
        }
    }

    var tab_draft = $('.dp_tab_draft');
    var flex = false;
    $('.sort').click(function () {
        //
        tab_draft.find('li').removeClass('on');
        $(this).addClass('on');
        var pos = $(this).attr('data-sort');
        if (pos === 'FLEX') {
            flex = true;
        } else {
            flex = false;
        }
        sort(pos);
    });
    $('.sort_table').css('cursor', 'pointer');
    $('.sort_table').click(function () {
        var table = $(this).parents('div').next().children('table').filter(function () {
            return $(this).css('display');
        });
        //
        var rows = table.find('tr').toArray();
        rows = rows.sort(comparer($(this).index()));
        //
        this.asc = !this.asc;
        if (!this.asc) {
            rows = rows.reverse();
        }
        for (var i = 0; i < rows.length; i++) {
            table.append(rows[i]);
        }
        //
        event.stopPropagation();
    });

    function comparer(index) {
        return function (a, b) {
            var valA = getCellValue(a, index), valB = getCellValue(b, index);
            return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
        };
    }

    function getCellValue(row, index) {
        return $(row).children('td').eq(index).html();
    }

</script>
<script>
    var total_salary = parseInt($('.total_salary').html().replace(',', ''));
    $('.del-player').css('cursor', 'pointer');
    // 해당 class로 된 엘리먼트의 총 개수와 현재 빈 자리 개수 구하기
    function chk_element_blank(pos) {
        var table = $('.lineup_' + pos);
        var count = table.length;
        table.find('.name').each(function () {
            if ($(this).html() !== '') {
                count--;
            }
        });
        if (count <= 0) {
            return false;
        } else {
            return count;
        }
    }

    function input_node(pos, node, text) {
        var table = $('.lineup_' + pos).find('.' + node);
        var count = table.length;
        //
        for (var i = 0; i < count; i++) {
            //
            var innet_eq = table.eq(i);
            if (innet_eq.html() === '') {
                innet_eq.html(text);
                return true;
            } else {
                if (node === 'name') {
                    if (text === innet_eq.html()) {
                        return false;
                    }
                }
                continue;
            }
        }
        return false;
    }
    //
    function chk_flex(data_flex, node, name) {
        var table = $('.lineup_' + data_flex).find('.' + node);
        if (table.html() === name) {
            return false;
        } else {
            return true;
        }
    }

    function addPlayer(pos, game_id, id, salary, name, team, data_flex) {

        if (total_salary - salary < 0) {
            alert('Salary exceeded the standard.');
            total_salary = parseInt(total_salary);
            return;
        } else {
            if (flex === true) {
                pos = 'FLEX';
                //
                if (chk_flex(data_flex, 'name', name) === false) {
                    alert('You have already selected that position3.');
                    return false;
                }
            }

            if (input_node(pos, 'name', name) === true) {
                total_salary = total_salary - salary;
            } else {
                alert('You have already selected that position.');
                return;
            }
            //
            input_node(pos, 'team', team);
            input_node(pos, 'point', '0');
            input_node(pos, 'salary', '$' + $.number(salary));
            input_node(pos, 'del', '<img class="del-player" src="<?= INC_PUBLIC ?>images/minus.png" data-game ="' + game_id + '" data-del-index ="' + id + '" onclick="delPlayer(\'' + id + '\')">');
            $('.total_salary').html($.number(total_salary));
            //
            var del_player = $('.del-player');
            del_player.css('cursor', 'pointer');
        }
    }

    function delPlayer(id) {
        var this_btn = $('[data-del-index="' + id + '"]');
        var salary = parseInt((this_btn.parent().parent().find('.salary').text()).replace('$', '').replace(',', ''));
        this_btn.parent().parent().find('.name').html('');
        this_btn.parent().parent().find('.team').html('');
        this_btn.parent().parent().find('.point').html('');
        this_btn.parent().parent().find('.salary').html('');
        this_btn.parent().parent().find('.del').html('');
        total_salary = total_salary + salary;
        $('.total_salary').html($.number(total_salary));
    }

    function init_add_player() {

        var add_player = $('.add_player');
        add_player.css('cursor', 'pointer');
        add_player.click(function () {
            var data_pos = $(this).attr('data-pos');
            var data_game = $(this).attr('data-game');
            var data_index = $(this).attr('data-index');
            var data_salary = $(this).attr('data-salary');
            var data_name = $(this).attr('data-name');
            var data_team = $(this).attr('data-team');
            var data_flex = $(this).attr('data-flex');
            //
            addPlayer(data_pos, data_game, data_index, data_salary, data_name, data_team, data_flex);
        });
    }

</script>
<script>
    $('.btn-clear').click(function () {
        $('.name').each(function () {
            $(this).html('');
        });
        $('.team').each(function () {
            $(this).html('');
        });
        $('.point').each(function () {
            $(this).html('');
        });
        $('.salary').each(function () {
            $(this).html('');
        });
        $('.del').each(function () {
            $(this).html('');
        });
        total_salary = total_salary - total_salary + 50000;
        $('.total_salary').html($.number(total_salary));
        event.stopPropagation();
    });</script>
<script>
    $('.btn-confrim-draft').click(function () {
        var coin = $(this).attr('data-coin');
        var category = $(this).attr('data-category');
        var game = $(this).attr('data-game');
        var data = {
            'id': '<?= $u_idx ?>',
            'coin': coin,
            'category': category,
            'game': game
        };
        draft_proccess(data);
        event.stopPropagation();
    });
    $('.btn-edit-draft').click(function () {
        var index = $(this).attr('data-index');
        var data = {
            'index': index
        };
        draft_proccess(data, '_edit');
        event.stopPropagation();
    });
    function draft_proccess(data, url) {
        var data = data;
        var go_url = '';
        if (url) {
            go_url = url;
        }

        data['player'] = {};
        var error = true;
        var del = $('.del').each(function (i) {
            if ($(this).html() === '') {
                alert('You must select all positions.');
                error = true;
                return false;
            } else {
                data['player'][i] = {};
                data['player'][i]['game_id'] = $(this).find('img').attr('data-game');
                data['player'][i]['player_id'] = $(this).find('img').attr('data-del-index');
                error = false;
            }
        });
        $.when(del).then(function () {
            if (error === false) {
                $.ajax({
                    url: 'ajax/draftgame' + go_url + '.php',
                    type: 'post',
                    data: data,
                    beforeSend: function () {
                        $('.btn-confrim-draft').attr('disabled', '');
                    },
                    success: function (data) {
                        console.log(data);
                        if (data === '100') {
                            alert('Completed');
                            location.replace('index.php?menu=contests');
                            return false;
                        } else {
                            $('.btn-confrim-draft').removeAttr('disabled', '');
                            alert('Error occurred');
                            return false;
                        }
                    }
                });
            }
        });
    }
</script>