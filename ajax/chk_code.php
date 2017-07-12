<?php

$code = filter_input(INPUT_POST, 'code');
$key = filter_input(INPUT_POST, 'key');

echo json_encode(array($code, $key));
