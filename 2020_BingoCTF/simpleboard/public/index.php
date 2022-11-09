<?php

require("init.php");
if(check_login()){
    switch($_SERVER['QUERY_STRING']){
        case "list":
            list_board();
            break;
        case "read":
            read_board($_POST['id']);
            break;
        case "write":
            write_board($_POST['title'], $_POST['content']);
            break;
        case "logout":
            @session_destroy();
            header("Location: /index.php");
            break;
        default:
            require("pages/after_login.php");
    }
}else{
    switch($_SERVER['QUERY_STRING']){
        case "login":
            login($_POST['username'], $_POST['password']);
            break;
        case "register":
            register($_POST['username'], $_POST['password']);
            break;
        default:
            require("pages/pre_login.php");
            break;
    }
}

?>
