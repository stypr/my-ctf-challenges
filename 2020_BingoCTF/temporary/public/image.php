<?php

require("init.php");
header("Content-Type: image/jpeg");
$url = @$_GET['url'];

// https://tools.ietf.org/html/rfc3986
// Thanks to RFC3986!
$filter = ["@", "?", "#", "[", "]", "\r", "\n"];

if($url){
    // Allow GitHub contents only.
    if(!preg_match("/^http(s?):\/\/raw.githubusercontent.com/im", $url)) die;
    // https://wiki.php.net/rfc/add_str_starts_with_and_ends_with_functions
    // PHP 8.0 has implemented new functions but the server is in PHP 7.4 :(
    if(substr_compare($url, ".jpg", -strlen(".jpg"))) die;
    foreach($filter as $_filter){
        if(stripos($url, $_filter) !== false) die;
    }
    // https://stackoverflow.com/questions/3629504/php-file-get-contents-very-slow-when-using-full-url
    // Not using file_get_contents() because it's too slow.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_HTTPHEADER, Array(
        "X-Real-IP: ". $_SERVER['REMOTE_ADDR'],
    ));
    $output = curl_exec($ch);
    if ((int)curl_getinfo($ch, CURLINFO_HTTP_CODE) > 399 || empty($output)) die;
    echo $output;
    curl_close($ch);
}

?>
