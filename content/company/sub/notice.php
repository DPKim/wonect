<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

$db_class = new DB_conn;
$conn = $db_class->dbconnect();

$qry_notice = "select * from notice order by nt_idx desc";
$result_notice = mysqli_query($conn, $qry_notice);

if ($result_notice) {
    $count_notice = mysqli_num_rows($result_notice);
    for ($i = 0; $i < $count_notice; $i++) {
        $arr_notice = mysqli_fetch_assoc($result_notice);

        // 날짜가 초까지는 필요 없자나?
        $date_notice = date('Y-m-d H:i', strtotime($arr_notice['nt_date']));
        $tr_notice .=<<<TR
                <tr class='li_notice' data-index="{$arr_notice['nt_idx']}">
                    <td>{$date_notice}</td>
                    <td style="text-align: left;">{$arr_notice['nt_subject']}</td>
                </tr>   
TR;
    }
}
?>
<div>
    <span class="dp_title_222">NOTICE</span>
</div>
<div class="company_content">
    <div class="dp_lineup_table" style="text-align: center">
        <table width="100%">
            <thead>
                <tr style="background:#42698e; color:#fff">
                    <td style="width:20%">Date</td>
                    <td style="width:80%; text-align: left;">Subject</td>
                </tr>
            </thead>
            <tbody id="table">
                <?= $tr_notice ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation">
            <ul class="pagination"></ul>
        </nav>
    </div>
    <button class="btn btn-default btn_write_notice">New Notice</button> <span style="color:red"><< 어드민 작업 후 삭제</span>
</div>