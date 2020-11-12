<?php

require("init.php");
if(check_login()){
    if($_SESSION['role'] == "admin"){
        switch($_SERVER['QUERY_STRING']){
            case "comment":
                comment_guestbook($_POST['id'], $_POST['comment']);
                break;
            default:
                require("pages/admin.php");
        }
    }else{
        switch($_SERVER['QUERY_STRING']){
            case "list":
                list_guestbook_user();
                break;
            case "ask":
                ask_guestbook($_POST['question']);
                break;
            case "logout":
                @session_destroy();
                header("Location: /index.php");
                break;
            default:
                require("pages/after_login.php");
        }
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
