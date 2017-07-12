<?php
include_once '../inc/config.php';
include_once CONTENT . 'class/class_result_game.php';

$g_idx = filter_input(INPUT_GET, 'index');

$class_uc_edit = new ResultGame($u_idx, $g_idx);

?>
<tr style="border-bottom : 1px solid #222">
    <td colspan="10" class="container_lineup" style="background:#333">
        <div class='contest_result'>
            <div class="result_rank">
                <table width='100%'>
                    <tr>
                        <td class="dp_bg_111">NAME</td>
                        <td class="dp_bg_111">RANK</td>
                        <td class="dp_bg_111">PRIZE</td>
                    </tr>
                    <?= $class_uc_edit->make_rank_table() ?>
                </table>
            </div>
            <div class="result_my">
                <div class="result_my_result">
                    <table width='100%'>
                        <?= $class_uc_edit->make_lineup_table()?>
                    </table>
                </div>
            </div>


            <div class="clearfix"></div>
        </div>
        <div>
            <button class="btn btn-default btn-sm dp_float_right btn_close" data-index="<?=$g_idx?>">
                <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                Close
            </button>
        </div>
    </td>
</tr>