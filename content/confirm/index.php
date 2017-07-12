<?php
$id= filter_input(INPUT_GET, 'id');
$chk_key= filter_input(INPUT_GET, 'key');
?>
<div class="container text-center dp_login">
    <a href="index.php?meun=lobby"><img src="<?= INC_PUBLIC ?>images/logo.png"></a>
    <h1>ONE-DAY FANTASY&nbsp;SPORTS</h1>
    <h2>JOIN SPO-BIT. WIN&nbsp;CASH.</h2>
    <?php
    require_once $sub.'/index.php';
    ?>
    <div>
        © 2016 - 2017 Aman International Inc., All Rights Reserved
    </div>
</div>