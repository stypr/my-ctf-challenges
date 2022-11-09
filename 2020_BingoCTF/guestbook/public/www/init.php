<?php

require(__DIR__ . "/vendor/autoload.php");

// Hide errors
ini_set("display_errors","off");
error_reporting(0);

// To prevent XSS
$users = $guestbook = 0;
$seed = md5(rand(PHP_INT_MIN,PHP_INT_MAX));
if($_GET['health']) die(hash("sha256", $seed));
ini_set('session.cookie_httponly', 1);
session_name("guestbook");
session_start();
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block;');
header("Content-Security-Policy: default-src 'self' 'nonce-script'; object-src 'none'; base-uri 'none'; trusted-types");

// Copied from stackoverflow.com
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function init_db(){
    global $users, $guestbook, $files;
    $db = "/tmp/db";
    $users = \SleekDB\SleekDB::store("users", $db);
    $guestbook = \SleekDB\SleekDB::store("guestboard", $db);
    // To prevent DoS
    if(
        count($users->fetch()) >= 0xffff ||
        count($guestbook->fetch()) >= 0xffff
    ){
        rrmdir($db);
        $users = \SleekDB\SleekDB::store("users", $db);
        $guestbook = \SleekDB\SleekDB::store("guestboard", $db);
    }
}

function init_insert(){
    global $users, $guestbook, $files;
    $note = [
        "username" => "admin",
        "question" => "I'm testing a new content.",
        "comment" => "",
    ];
    $admin = [
        "username" => "admin",
        "password" => "__4DM1N_P4SSW0RD__",
        "role" => "admin",
    ];
    $admin_check = $users->where("username", "=", "admin")->fetch();
    if(!$admin_check) $users->insert($admin);
    $guestbook_check = $guestbook->where("username", "=", "admin")->fetch();
    if(!$guestbook_check) $guestbook->insert($note);
}

function rrmdir($dir, $depth=0){
    if (is_dir($dir)){
        $objects = scandir($dir);
        foreach ($objects as $object){
            if ($object != "." && $object != ".."){
                if(is_dir($dir."/".$object))
                    rrmdir($dir."/".$object, $depth + 1);
                else
                    unlink($dir."/".$object);
            }
        }
    }
    if($depth != 0) rmdir($dir);
}

function register($username, $password){
    global $users, $guestbook, $files;
    $username = (string)$username;
    $password = (string)$password;

    if($username && $password){
        // I trust SleekDB, but just in case :)
        $username = str_replace(["'", '"'], "", $username);
        $password = str_replace(["'", '"'], "", $password);
        if(strlen($username) > 20 || strlen($username) < 4 ||
           strlen($password) > 20 || strlen($password) < 4){
            die("too long or too short");
        }
        $user_check = $users->where("username", "=", $username)->fetch();
        if($user_check){
            die("username already exists..");
        }
        $user = [
            "username" => $username,
            "password" => $password,
            "role" => "user",
        ];
        $users->insert($user);
        die("done");
    }
}

function login($username, $password){
    global $users, $guestbook, $files;
    $username = (string)$username;
    $password = (string)$password;

    if($username && $password){
        // I trust SleekDB, but just in case :)
        $username = str_replace(["'", '"'], "", $username);
        $password = str_replace(["'", '"'], "", $password);
        if(strlen($username) > 20 || strlen($username) < 4){
            die("too long or too short");
        }
        $user_check = $users->where("username", "=", $username)->where("password", "=", $password)->fetch();
        if($user_check){
            $_SESSION['username'] = $user_check[0]['username'];
            $_SESSION['password'] = md5($user_check[0]['password']);
            $_SESSION['role'] = $user_check[0]['role'];
            die("success");
        }else{
            die("invalid user");
        }
    }
}

function check_login(){
    if($_SESSION['username'] && $_SESSION['password'] && $_SESSION['role']){
        return true;
    }
    return false;
}

function comment_guestbook($id, $comment){
    global $users, $guestbook;
    // I trust SleekDB, but just in case :)
    $id = (int)$id;
    $comment = str_replace(["'", '"'], "", $comment);
    if($comment){
        if(strlen($comment) > 100){
            die("too long");
        }
        if(stripos($comment, "<") !== false ||
           stripos($comment, ">") !== false){
            die("xss blocked");
        }
        $update_comment = [
            "comment" => $comment
        ];
        $guestbook->where('_id', '=', $id)->update($update_comment);

        die("done");
    }
}

function ask_guestbook($question){
    global $users, $guestbook;
    // I trust SleekDB, but just in case :)
    $id = (int)$id;
    $question = str_replace(["'", '"'], "", $question);

    if($question){
        if(strlen($question) > 1024){
            die("too long");
        }
        $insert_question = [
            "username" => (string)$_SESSION['username'],
            "question" => $question,
            "comment" => "",
        ];
        $result = $guestbook->insert($insert_question);

        if ($result) {
            try {
                $redis = new Redis();
                $redis->connect("redis", 6379);
                $redis->rPush("query", $result['_id']);
            } catch (Exception $e) {
                print($e);
                exit(0);
            }
        }
        die("done");
    }
}

function list_guestbook_user(){
    global $users, $guestbook;
    header("Content-Type: application/json");
    $guestbook_check = $guestbook->where("username", "=", (string)$_SESSION['username'])->fetch();
    if($guestbook_check){
        die(json_encode($guestbook_check));
    }else{
        die(json_encode([]));
    }
}

function list_guestbook_admin($id){
    global $users, $guestbook;
    if(!$id){
        $guestbook_check = $guestbook->where("comment", "=", "")->where("_id", "=", $id)->orderBy('desc', '_id')->limit(1)->fetch();
    }else{
        $guestbook_check = $guestbook->where("comment", "=", "")->orderBy('desc', '_id')->limit(1)->fetch();
    }
    if($guestbook_check){
        $update_comment = [
            "comment" => "ㄴ Admin did not reply on your question. You can ask other messages."
        ];
        $guestbook->where('_id', '=', $guestbook_check[0]['_id'])->update($update_comment);
    }
    return $guestbook_check[0];
}

init_db();
init_insert();

?>
