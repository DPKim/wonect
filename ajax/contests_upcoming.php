<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';
include_once CONTENT . 'class/class_contests.php';
include_once CONTENT . 'class/class_makeNavi.php';

$category = filter_input(INPUT_POST, 'category');
$click_page = filter_input(INPUT_POST, 'page');

$class_upcoming = new Contest_upcoming($u_idx, $category, $click_page, $limit_row, $limit_navi, 0);
$total_count = $class_upcoming->selectDB_total()['count'];
$navi = new MakeNavi($total_count, $limit_row, $limit_navi, $click_page);
?>
<table class="dp_draft_table">
    <tbody><tr>
            <td style="width:20%">Contest Name</td>
            <td style="width:8%">Status</td>
            <td style="width:8%">Invite</td>
            <td style="width:8%">Enter</td>
            <td style="width:16%">Live In</td>
            <td style="width:8%">Places Paid</td>
            <td style="width:8%">Total Prize</td>
            <td style="width:8%">Entries</td>
            <td style="width:8%">Entry Fee</td>
            <td style="width:8%">Top Prize</td>
        </tr>
    </tbody>
</table>
<div class="dp_draft_choice_lineup">
    <table class="dp_draft_table2">
        <tbody class="dp_font_fff">
            <?php
            foreach ($class_upcoming->selectContest() as $value) {
                echo $value;
            }
            ?>
        </tbody>
    </table>
</div>
<div class="clearfix"></div>
<nav aria-label="Page navigation">
    <ul class="pagination" style="margin-bottom: 0px">
        <?= $navi->makeNavi() ?>
    </ul>
</nav>
<input id="total_winning" type="hidden" value="<?= $class_upcoming->posible_winning ?>">