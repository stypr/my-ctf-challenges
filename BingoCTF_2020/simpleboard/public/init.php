<?php

// require("flag.php");
define("__FLAG__", "<!-- The flag is in flag.php! -->");
ob_flush();
require(__DIR__ . "/vendor/autoload.php");


// Hide errors
ini_set("display_errors","off");
error_reporting(0);

// To prevent XSS
$users = $board = $files = 0;
$seed = md5(rand(PHP_INT_MIN,PHP_INT_MAX));
if($_GET['health']) die(hash("sha256", $seed));
ini_set('session.cookie_httponly', 1);
session_name("simpleboard");
session_start();
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block;');
header('X-Content-Type-Options: nosniff');
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

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
    global $users, $board, $files;
    $db = "/tmp/db";
    $users = \SleekDB\SleekDB::store("users", $db);
    $files = \SleekDB\SleekDB::store("files", $db);
    $board = \SleekDB\SleekDB::store("board", $db);

    // To prevent DoS
    if(
        count($users->fetch()) >= 0xff ||
        count($board->fetch()) >= 0xff ||
        count($files->fetch()) >= 0xff
    ){
        rrmdir($db);
        $users = \SleekDB\SleekDB::store("users", $db);
        $board = \SleekDB\SleekDB::store("board", $db);
        $files = \SleekDB\SleekDB::store("files", $db);
    }

}

function init_insert(){
    global $users, $board, $files;
    $post1 = [
        "title" => "I made a new website~",
        "author" => "admin",
        "content" => "Hi, I'm stypr. I've created a secure anonymous board. We're looking for a disaster-level hacker who can pwn our service! :)"
    ];
    $post2 = [
        "title" => "This website is secure from XSS!",
        "author" => "stypr",
        "content" => "We've hidden session ID! It's now completely secure. <script>alert(document.cookie);</script>"
    ];

    $admin = [
        "username" => "admin",
        "password" => generateRandomString(64),
        "role" => "admin",
    ];
    $admin_check = $users->where("username", "=", "admin")->fetch();
    if(!$admin_check) $users->insert($admin);
    $board_check = $board->where("author", "=", "stypr")->fetch();
    if(!$board_check) $board->insert($post1);
    if(!$board_check) $board->insert($post2);
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

if(isset($_GET['image'])) read_image($_GET['image']);

function register($username, $password){
    global $users, $board, $files;
    $username = (string)$username;
    $password = (string)$password;

    if($username && $password){
        // I trust SleekDB, but just in case :)
        $username = str_replace(["'", '"'], "", $username);
        $password = str_replace(["'", '"'], "", $password);
        if(strlen($username) > 20 ||
           strlen($password) > 20){
            die("too long");
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
    global $users, $board, $files;
    $username = (string)$username;
    $password = (string)$password;

    if($username && $password){
        // I trust SleekDB, but just in case :)
        $username = str_replace(["'", '"'], "", $username);
        $password = str_replace(["'", '"'], "", $password);
        if(strlen($username) > 20 ||
           strlen($password) > 20){
            die("too long");
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
        if($_SESSION['role'] == "admin" && $_SESSION['role'] === "admin"){
            die(__FLAG__);
        }
        return true;
    }
    return false;
}

function hide_username($board_data){
    // Anonymous board
    $fetch = [];
    foreach($board_data as $board_content){
        $board_content["author"] = substr(md5($board_content["author"] . "saltsalt"), 0, 6);
        $fetch[] = $board_content;
    }
    return $fetch;
}

function write_board($title, $content){
    global $users, $board, $files;
    // I trust SleekDB, but just in case :)
    $title = str_replace(["'", '"'], "", $title);
    $content = str_replace(["'", '"'], "", $content);
    if($content && $title){
        if(strlen($content) > 100 ||
           strlen($title) > 20){
            die("too long");
        }
        if(stripos($content, "<") !== false ||
           stripos($content, ">") !== false ||
           stripos($title, "<") !== false ||
           stripos($title, ">") !== false){
            die("xss blocked");
        }
        $post = [
            "title" => $title,
            "author" => $_SESSION['username'],
            "content" => $content
        ];
        $board->insert($post);
        die("done");
    }
}

function read_board($id){
    global $users, $board, $files;
    header("Content-Type: application/json");
    $board_check = $board->where("_id", "=", (int)$id)->fetch();
    if($board_check){
        $board_check = hide_username($board_check);
        die(json_encode($board_check));
    }else{
        die(json_encode([]));
    }
}

function list_board(){
    global $users, $board, $files;
    header("Content-Type: application/json");
    $board_check = hide_username($board->fetch());
    die(json_encode($board_check,true));
}

function read_image($filename){
    header("Content-Type: image/png");
    if(preg_match("/(^((?:[0-9a-fA-F])+)(\.png){0,1})|(^((?:[0-9a-fA-F])+)(\.jpg){0,1})/", $filename)){
        include("./images/".$filename);
        exit;
    }
    die("invalid file");
}


init_db();
init_insert();
?>
