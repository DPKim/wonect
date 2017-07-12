<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once '../inc/config.php';
include_once CONTENT . 'class/class_rank_reward.php';

$name = filter_input(INPUT_POST, 'name');
$size = filter_input(INPUT_POST, 'size');
$fee = filter_input(INPUT_POST, 'fee');
$multi = filter_input(INPUT_POST, 'multi');
$prize = filter_input(INPUT_POST, 'prize');
$prize_name = prize_type($prize);

$reward = new RankReward($size, $fee, $prize);
?>
<table class="dp_draft_table">
    <thead>
        <tr>
            <td style="width:55%">Contest Name</td>
            <td style="width:10%">Size</td>
            <td style="width:10%">Fee</td>
            <td style="width:10%">Multi</td>
            <td style="width:15%">Prize Structure</td>
        </tr>
    </thead>
    <tbody>
        <tr style="background: #fff; color: #222">
            <td><?= $name ?></td>
            <td><?= $size ?></td>
            <td><?= $fee ?> G</td>
            <td><?= $multi ?></td>
            <td><?= $prize_name ?></td>
        </tr>
    </tbody>
</table>
<h3 style="text-align: left">Reward</h3>
<table class="dp_draft_table">
    <thead>
        <tr>
            <td style="width:40%">Ranking</td>
            <td style="width:20%">Size</td>
            <td style="width:20%">Prize / size</td>
            <td style="width:20%">Total Prize</td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($reward->make_rank_arr() as $value) {
            $total = $value['reward'] * $value['limit'];
            $tr .= <<<TR
                <tr style="background: #fff; color: #222; border-bottom: 1px solid #ddd">
                    <td>{$value['rank']}</td>
                    <td>{$value['limit']}</td>
                    <td>{$value['reward']} G</td>
                    <td>{$total} G</td>
                </tr>
TR;
        }
        echo $tr;
        ?>
    </tbody>
</table>