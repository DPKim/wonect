<?php
include_once '../inc/config.php';

$now_pw = filter_input(INPUT_POST, 'pw');
$qry = "select * from members where m_idx = $u_idx and m_pw = '$now_pw'";
$result = mysqli_query($conn, $qry);
$count = mysqli_num_rows($result);

if ($count > 0) {
    $div = <<< DV
        <div class="modal-body">
            <p>New Passwords</p>
            <div>
                <input id="new_pw" class="form-control" type="password">
            </div>
            <p style="margin-top:10px">Passwords Confirm</p>
            <div>
                <input id="cf_new_pw" class="form-control" type="password">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn_new_pw">Change Passwords</button>
        </div>            
DV;
    echo $div;
} else {
    echo 500;
}
?>