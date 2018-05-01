<?php
    /* Weeb is weed. */
    function generate_salt(){
        $rand_seed = (mktime(date("H"),0,0,date("n"),date("j"),date("Y")) * 1337) % PHP_INT_MAX;
        mt_srand($rand_seed);
        $c = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $l = strlen($c);
        $s = '';
        for ($i = 0; $i < 64; $i++) {
           $s .= $c[mt_rand(0, $l - 1)];
        }
        return $s;
    }
    define("__FLAG__", "ASIS{PLZ_GIVE_ME_SER1ALS_AND_CHUN1THM}");
    define("__SALT__", generate_salt());
    define("__DATA__", "data/user");
    define("__PASV__", "data/board");
    define("__SECU__", "/var/www/html/aad407a75bda64301f88d29bae5dd799/data/security/");
    header("Cache-Control: max-age=0");
    error_reporting(0);
    ini_set('display_errors', 'off');
    if(strpos($_SERVER['SCRIPT_NAME'], "/lib.php") !== false){
        die('<iframe id="ytplayer" type="text/html" width="640" height="360" src="https://www.youtube.com/embed/CocwP6W9Ue0?autoplay=1" frameborder="0"/></iframe><!-- ofc you\'re kicking up the wrong door. -->');
    }
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
?>
