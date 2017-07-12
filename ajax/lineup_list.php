<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';
include_once CONTENT . 'class/class_lineups.php';
include_once CONTENT . 'class/class_makeNavi.php';

$category = filter_input(INPUT_POST, 'category');
$click_page = filter_input(INPUT_POST, 'page');

// 기본 페이지 노출 및 리스트 제한 설정
$limit_row = 6;
$limit_navi = 10;

$lineup = new Lineups($u_idx, $category, $click_page, $limit_row, $limit_navi);
foreach ($lineup->selectLineups() as $value) {
    echo $value;
}

$total_count = $lineup->selectDB_lineup_total($category)['count'];
$navi = new MakeNavi($total_count, $limit_row, $limit_navi, $click_page);
?>
<?php
if ($total_count > 0) {
    ?>
    <div class="clearfix"></div>
    <nav aria-label="Page navigation">
        <ul class="pagination" style="margin-bottom: 0px">
            <?= $navi->makeNavi() ?>
        </ul>
    </nav>
    <?php
}
?>