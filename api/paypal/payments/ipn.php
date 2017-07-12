<?php

// Choose url
if(array_key_exists('test_ipn', $ipn_post_data) && 1 === (int) $ipn_post_data['test_ipn'])
    $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
else
    $url = 'https://www.paypal.com/cgi-bin/webscr';

// Set up request to PayPal
$request = curl_init();
curl_setopt_array($request, array
(
    CURLOPT_URL => $url,
    CURLOPT_POST => TRUE,
    CURLOPT_POSTFIELDS => http_build_query(array('cmd' => '_notify-validate') + $ipn_post_data),
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HEADER => FALSE,
));

// Execute request and get response and status code
$response = curl_exec($request);
$status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

// Close connection
curl_close($request);

if($status == 200 && $response == 'VERIFIED')
{
    // All good! Proceed...
}
else
{
    // Not good. Ignore, or log for investigation...
}
