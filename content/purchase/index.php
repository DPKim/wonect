<?php
$type = filter_input(INPUT_GET, 'type');
$result = filter_input(INPUT_GET, 'result');

if ($result == '100') {
    ?>
    <div class="container text-center dp_login">
        <a href="index.php?meun=lobby"><img src="<?= INC_PUBLIC ?>images/logo.png"></a>
        <h1>Purchase Completed</h1>
        <h2>JOIN SPO-BIT. WIN&nbsp;CASH.</h2>
        <button class="btn btn-primary btn-lg" style="margin: 30px 0 30px 0" onclick="location.replace('index.php?menu=lobby')">GO HOME</button>
        <div>
            © 2016 - 2017 Aman International Inc., All Rights Reserved
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="container text-center dp_login">
        <a href="index.php?meun=lobby"><img src="<?= INC_PUBLIC ?>images/logo.png"></a>
        <h1>Error Occured</h1>
        <h2>JOIN SPO-BIT. WIN&nbsp;CASH.</h2>
        <button class="btn btn-primary btn-lg" style="margin: 30px 0 30px 0" onclick="location.replace('index.php?menu=store')">GO STORE</button>
        <div>
            © 2016 - 2017 Aman International Inc., All Rights Reserved
        </div>
    </div>
    <?php
}
?>