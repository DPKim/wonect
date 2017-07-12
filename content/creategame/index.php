<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

$get_step = filter_input(INPUT_GET, 'step');
$get_cate = filter_input(INPUT_GET, 'cate');
$get_val = filter_input(INPUT_GET, 'val');

if (!isset($get_step)) {
    $get_step = 1;
}

if (!isset($get_cate)) {
    $get_cate = 0;
}
if (!isset($get_val)) {
    $get_val = 1;
}

$db_game = new CreateGame($get_step, $get_cate);
$stepFunc = 'proccess_' . $get_step;

$css_step = 'step' . $get_step;
if (!$get_step) {
    $step1 = 'class="current"';
} else {
    $$css_step = 'class="current"';
}

switch ($get_step) {
    case 1:
        $require = 'sub/step_1.php';
        break;
    case 2:
        $require = 'sub/step_2.php';
        break;
    case 3:
        $require = 'sub/step_3.php';
        $get_json_data = urldecode($get_val);
        break;
    case 4:
        $require = 'sub/step_4.php';
        $get_json_data = urldecode($get_val);
        break;
    case 5:
        $require = 'sub/step_5.php';
        $get_json_data = urldecode($get_val);
        $arr_json_data = json_decode($get_json_data, true);
        break;
    default:
        $require = 'sub/step_1.php';
        break;
}
?>
<div id="back_box" class="dp_cotent_box">
    <div class="container dp_main_sidemenu">
        <div class="create_title">
            <span>CREATE A CONTEST</span>
        </div>
        <div class="wizard">
            <div <?= $step1 ?>><span class="badge">1</span> Sport</div>
            <div <?= $step2 ?>><span class="badge">2</span> Time</div>
            <div <?= $step3 ?>><span class="badge">3</span> Type</div>
            <div <?= $step4 ?>><span class="badge">4</span> Details</div>
            <div <?= $step5 ?>><span class="badge">5</span> Confirm</div>
        </div>
        <?php
        require_once $require;
        ?>
    </div>
</div>
<div id="cal_content"></div>