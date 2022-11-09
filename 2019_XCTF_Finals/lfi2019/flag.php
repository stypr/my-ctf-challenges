<?php
    $flag = "FLAG{this_surely_is_a_leg1timate_f!le_1nclusion}";

    if(stripos($_SERVER['SCRIPT_NAME'], "flag.php") !== false){
        die("<!-- flag.php successfully loaded. -->");
    }
?>