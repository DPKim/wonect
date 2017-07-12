<?php

include_once '../inc/config.php';
include_once CONTENT . 'class/class_lineups.php';
include_once CONTENT . 'class/class_makeNavi.php';

$g_idx = filter_input(INPUT_GET, 'index');

$lineup = new Lineups($u_idx, $category, $click_page, $limit_row, $limit_navi);
foreach ($lineup->selectLineups($g_idx) as $value) {
    $div .=$value ;
}

$tr = <<<TR
        <tr style="border-bottom : 1px solid #222">
            <td colspan="10" class="container_lineup" style="background:#333">
                <div class='list_lineup'>
                    $div
                    <div class="clearfix"></div>
                </div>
                <div>
                    <button class="btn btn-default btn-sm dp_float_right btn_close" data-index="$g_idx">
                        <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                        Close
                    </button>
                </div>
            </td>
        </tr>
TR;
echo $tr;
?>