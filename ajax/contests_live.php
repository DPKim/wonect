<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';
include_once CONTENT . 'class/class_contests.php';
include_once CONTENT . 'class/class_makeNavi.php';

$category = filter_input(INPUT_POST, 'category');
$click_page = filter_input(INPUT_POST, 'page');

$class_live = new Contest_live($u_idx, $category, $click_page, $limit_row, $limit_navi, 2);
$total_count = $class_live->selectDB_total()['count'];
$navi = new MakeNavi($total_count, $limit_row, $limit_navi, $click_page);
?>
<table class="dp_draft_table">
    <tbody>
        <tr>
            <td style="width:28%">Contest Name</td>
            <td style="width:9%">Place</td>
            <td style="width:9%">Winning</td>
            <td style="width:9%">My Score</td>
            <td style="width:9%">Top Score</td>
            <td style="width:9%">My Rank</td>
            <td style="width:9%">Entry Fee</td>
            <td style="width:9%">Top Prize</td>
        </tr>
    </tbody>
</table>
<div class="dp_draft_choice_lineup">
    <table class="dp_draft_table2">
        <tbody class="dp_font_fff">
            <?php
            foreach ($class_live->selectContest() as $value) {
                echo $value;
            }
            ?>
        </tbody>
    </table>
</div>
<?php
if ($total_count > 0) {
    ?>
    <div class="clearfix"></div>
    <nav aria-label="Page navigation">
        <ul class="pagination" style="margin-bottom: 0px">
            <?= $navi->makeNavi() ?>
        </ul>
    </nav>
    <input id="total_winning" type="hidden" value="<?= $class_live->posible_winning ?>">
    <?php
}
?>