<?php
$chk = explode('|', decrypt($key, $chk_key));

$qry = "select * from members ";
$qry .= "where m_id = '$chk[2]' ";

if ($id !== $chk[1]) {
    $is_false = false;
} else {
    $result = mysqli_query($conn, $qry);
    if ($result) {
        $arr = mysqli_fetch_assoc($result);

        if ($arr['m_check'] == 1) {
            $is_false = false;
        } else {
            if ($arr['m_name'] !== $chk[1]) {
                $is_false = false;
            } else {
                $qry_up = "update members set m_check = 1 where m_idx = {$arr['m_idx']}";
                $result_up = mysqli_query($conn, $qry_up);

                if ($result_up) {
                    $is_false = true;
                    $_SESSION['fsport']['index'] = $arr['m_idx'];
                    $_SESSION['fsport']['id'] = $arr['m_id'];
                }
            }
        }
    } else {
        $is_false = false;
    }
}

if ($is_false == true) {
    ?>
    <div class="jumbotron" style="width: 700px; margin: 30px auto; color:#222;">
        <div style="text-align: center; font-size: 30px">
            <h3 class="modal-title" >Your e-mail Confirmed.</h3>
            <button type="button" class="btn btn-primary btn-lg btn_go_main" style="margin-top: 10px">
                ENJOY THE GAME
            </button>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="jumbotron" style="width: 700px; margin: 30px auto; color:#222;">
        <div style="text-align: center; font-size: 30px">
            <h3 class="modal-title" >Confirmation code not valid.</h3>
            <button type="button" class="btn btn-primary btn-lg btn_go_main" style="margin-top: 10px">
                SIGN IN
            </button>
        </div>
    </div>
    <?php
}
?>