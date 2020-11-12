<?php

ini_set("display_errors", "off");
error_reporting(0);

function flag(){
    $ip = $_SERVER["HTTP_X_REAL_IP"];
    $host_ip = explode(".", $_SERVER['REMOTE_ADDR']);
    $host_ip = "$host_ip[0].$host_ip[1].$host_ip[2].1";
    try{
        $result = trim(file_get_contents("http://" . $host_ip . ":295/?mode=af7d01440c043edb0677cab2918aa251&ip=" . $ip . "&challenge_name=temporary"));
        if($result == "" || $result == "Crash"){
            die("501 -- Contact Admin");
        }
        return $result;
    } catch (Exception $e) {
        die("500 -- Contact Admin");
    }
}

$flag = flag();
if($flag){
    echo "<h1>stypr's Internal services</h1>\n";
    echo $flag;
}

?>
