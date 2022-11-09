<?php
/*
    Developed in PHP7,
    by the *kawaii-imouto-daisuki* PHP scientist.
*/
/*
    Baka-Baka Flag-chan,
    Kawaii Salt-chan,
    and of course, waifu Sagiri-chan!
    $$$ ALL INCLDUED EXCLUSIVELY! $$$

    BEWARE, Salt-chan changes herself every hour!
*/
require('lib.php');
/*
    Why Camellia cipher?
    Because I love Japan.
    This algorithm should be still secure in 2018, I guess. :p

    * From kakkoii wikipedia-kun *
    - First published 2000
    - Designers   Mitsubishi Electric, NTT << Look here, it's Japan!
    - Derived from    E2, MISTY
*/
define("__CIPHER__", "camellia-256-cbc");
/*
    Why MicroDB?
    Because I'm too noob to use SQLite.
    It's simple and faster to use MicroDB.
    morris/microdb, at master branch.
*/
foreach(glob("./morris/microdb/src/MicroDB/*.php") as $microdb){
    @require($microdb);
}
/*
    Why Affimojas-kun?
    Because He hates kawaii Neptune-chan and
    He doesn't like to be attacked by anyone, too.
*/
class Affimojas {
    protected $dir = __SECU__;
    private $path;
    private $ban = 3600; // subsequent bantime from attacks
    public function __destruct(){
        $caller = get_class(debug_backtrace()[1]['object']);
        if(in_array($caller, ["Neptune", "Uzume", "Affimojas"])){
            if($this->flag == __FLAG__){
                die(__FLAG__);
            }
        }else{
            $this->add_count("Affimojas Mayday");
            die("Too bad, it's not a good way to wake me up, Hacker-kun! (" . $this->get_count() . "/128)");
        }
    }
    public function __construct(){
        $this->path = $this->dir . sha1($_SERVER['REMOTE_ADDR'] . __SALT__);
        if(!file_exists($this->path)){
            $fp = @fopen($this->path, "a+");
            @fwrite($fp, date('Y-m-d H:i:s') . "-" . $_SERVER['REMOTE_ADDR'] . "\n");
            @fclose($fp);
        }
    }
    private function get_count(){
        // What could go wrong?
        $count = 0;
        $cur = time();
        if(!file_exists($this->path)){
            die("<h1>Error found. masaka... T^T</h1>");
        }
        $fp = fopen($this->path, "rb");
        while(!feof($fp)){
            $line = fgets($fp);
            if($count >= 1){
                $_time = (int)trim(explode(".", $line)[0]);
                if($cur >= $_time + $this->ban || $cur <= $_time - $this->ban){
                    continue;
                }
            }
            $count++;
        }
        fclose($fp);
        $count -= 1;
        return $count;
    }
    public function add_count($data){
        $fp = @fopen($this->path, "a+");
        $data = preg_replace('/\s+/', '', $data);
        @fwrite($fp, time() . ".Invalid Security Access(" . $data . ")\n");
        @fclose($fp);
    }
    private function filter_trials(){
        // Brute-forcing yamero~~~~!!! //
        $count = $this->get_count();
        if($count >= 128){
            return false;
        }
        return true;
    }
    private function filter_injection($data){
        $filter = ['.', 'html', __FLAG__, 'bash', 'etc', 'proc', 'file:', 'user:', 'gopher:', 'http:', 'php', 'phtml'];
        if(is_array($data)) return false;
        foreach($filter as $filter_check){
            if(substr_count(strtolower($data), strtolower($filter_check)) > 2){
                return false;
            }
        }
        return $data;
    }
    private function filter_session($data){
        if(is_array($data)) return false;
        $data = str_ireplace(";O:", ";s:", $data);
        $secure_except = ';s:9:"Affimojas":3:';
        if(substr_count($data, $secure_except) == 1){
            $data = str_ireplace($secure_except, ';O:9:"Affimojas":3:', $data);
        }
        $filter = ['asis', 'admin', __FLAG__, 'kawaii', 'StdClass', 'Object', 'String'];
        foreach($filter as $filter_check){
            if(substr_count(strtolower($data), strtolower($filter_check)) > 0) return false;
        }
        $filter = ['"Uzume"', '"Neptune"', '"Affimojas"', 'Database"'];
        foreach($filter as $filter_check){
            if(substr_count(strtolower($data), strtolower($filter_check)) > 1) return false;
        }
        return $data;
    }
    public function waf($data){
        if($this->filter_trials()){
            if(!$data){
                $this->add_count("Malformed data");
                die("Kono-yaro! Malformed data yamero~!!!!!");
            }
            $i = $this->filter_injection($data);
            $s = $this->filter_session($i);
            if(!$i || !$s){
                $this->add_count($data);
                die("Kono-Yaro! You cannot get me, hahaha!");
            }else{
                return $s;
            }
        }else{
            die("Baka Onii-chan! You are blocked from access. Please wait for some time.");
        }
    }
}
/*
    Why Uzume-chan?
    Because she is kawaii. One day she will be my imouto!
    https://goo.gl/9e1c3B
*/
class Uzume {
    public $flag = 0;
    private $waf;
    function __construct($flag){
        $this->flag = $flag;
        $this->waf = new Affimojas();
    }
    function __destruct(){
        if(!is_array($this->flag) && !is_string($this->flag) && !is_null($this->flag)){
            if((string)$this->flag['ASIS'] == "kawaii~"){
                die(__FLAG__);
            }
        }
    }
    function list(){
        $db = new \MicroDB\Database(__PASV__);
        $result = [];
        $posts = $db->find(function($post){ return true; });
        return $posts;
    }
    function read($id){
        $db = new \MicroDB\Database(__PASV__);
        $post = $db->load($this->waf->waf($id));
        if(!$post){ return false; }
        return $post;
    }
    function write($title, $content, $by){
        $db = new \MicroDB\Database(__PASV__);
        $max = 2;
        if(count($check) >= $max){
            for($i=1;$i<=$max;$i++){
                @$db->delete($i);
            }
        }
        $id = $db->create(array(
           'title' => $this->waf->waf($title),
           'content' => $this->waf->waf($content),
           'date' => date("Y-m-d H:i:s"),
           'by' => $this->waf->waf($by),
        ));
    }
}
/*
    Among all my waifus,
    Neptune-chan is the best waifu.
    https://goo.gl/YxAM9Y
*/
class Neptune {
    protected $cipher = __CIPHER__;
    private $username = "";
    private $password = "";
    private $coin = 0;
    private $waf;
    function __construct($username='', string $password=''){
        if($this->username) return;
        $this->username = $username;
        $this->password = $password;
        $this->waf = new Affimojas();
    }
    function __destruct(){
        if(is_string($this->username) && is_string($this->password)){
            if((string)$this->username == "Neptune"){
                if((string)$this->password == sha1(__SALT__ . __SALT__)){
                    die(__FLAG__);
                }
            }
        }
    }
    function _auth(string $username, string $password){
        $db = new \MicroDB\Database(__DATA__);
        $check = $db->find();
        foreach($check as $key => $val){
            if($val['id'] == $username &&
               $val['pw'] === sha1(__SALT__ . $password)){
                return true;
            }
        }
        return false;
    }
    function verify($session){
        $iv = hex2bin(substr($session, 0, 32));
        $ctext = hex2bin(substr($session, 32));
        $ptext = @openssl_decrypt($ctext, $this->cipher, __SALT__, $options=OPENSSL_RAW_DATA, $iv=$iv);
        if(!$ptext) $this->bye();
        $v = @unserialize($this->waf->waf($ptext));
        if(!$v){
            $this->bye();
            $this->waf->add_count('Malformed Session');
            $this->bye();
        }
        foreach($v as $key => $val){
            if(ctype_print($key)){
                try{ $this->$key = $val; }catch(Exception $e){ return false; }
            }
        }
        $auth = $this->_auth($this->username, $this->password);
        if($auth === True){
            return [$this->username, $this->password, $this->coin];
        }else{
            return false;
        }
    }
    function save(){
        global $key;
        $iv = random_bytes(16);
        $enc = bin2hex($iv) . bin2hex(openssl_encrypt(serialize($this), 'camellia-256-cbc', __SALT__, $options=OPENSSL_RAW_DATA, $iv));
        setcookie("donmai", $value = $enc, $expire = time() + 86400 * 30, "/", $_SERVER['HTTP_HOST']);
    }
    function auth(string $username, string $password){
        if(!(ctype_alnum($username))){
            return ['error', 'Please check your information and try again.'];
        }
        if(strlen($username) > 20 || strlen($password) > 80){
            return ['error', 'Input is too long. Try again.'];
        }
        if(strlen($username) < 5 || strlen($password) < 5){
           return ['error', 'Input is too short. Make it secure!'];
        }
        $auth = $this->_auth($username, $password);
        if($auth === True){
            $this->username = $username;
            $this->password = $password;
            $this->save();
            return ['success', 'Ohayou, ' . $username . '-sama!'];
        }else{
            return ['error', 'Incorrect Credentials'];
        }
    }
    function join(string $username, string $password){
        if(!(ctype_alnum($username))){
            return ['error', 'Please do not use invalid characters!'];
        }
        if(strlen($username) > 20 || strlen($password) > 80){
            return ['error', 'Input is too long. Try again.'];
        }
        if(strlen($username) < 5 || strlen($password) < 5){
            return ['error', 'Input is too short. Make it secure!'];
        }

        $db = new \MicroDB\Database(__DATA__);
        $check = $db->find();
        $max = 256;
        $max_ip = 10;
        if(count($check) >= $max){
            for($i=1;$i<=$max;$i++){
                @$db->delete($i);
            }
        }
        $ip_cnt = 0;
        foreach($check as $key => $val){
            if($val['id'] == $username){
                return ['error', 'Duplicate ID'];
            }
            if($val['ip'] === $_SERVER['REMOTE_ADDR']){
                $ip_cnt++;
                if($ip_cnt >= 15){
                    return ['error', 'Too many requests from your IP, onii-chan!'];
                }
            }
        }
        $id = $db->create(array(
            'id' => $username,
            'pw' => sha1(__SALT__ . $password),
            'ip' => $_SERVER['REMOTE_ADDR'],
        ));
        return ['success', 'Registration complete~'];
    }
    function bye(){
        unset($_COOKIE['donmai']);
        setcookie("donmai", false, -1, "/", $_SERVER['HTTP_HOST']);
        if (headers_sent()) {
            die("NOPE");
        }
        return ['bye', 'See you, onii-chan!'];
    }
}
$nep = new Neptune();
$auth = false;
if(isset($_COOKIE['donmai'])){
    if($nep->verify($_COOKIE['donmai'])){
        $auth = true;
    }
}
if(!$auth){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = (string)$_POST['username'];
        $password = (string)$_POST['password'];
        if(isset($_POST['sign'])){
            $result = $nep->auth($username, $password);
            goto main;
        }
        if(isset($_POST['reg'])){
            $result = $nep->join($username, $password);
            goto main;
        }
    }
}

main:
?>
<!--
  こんにちは Hacker-sama!

  I decided to put some weebs instead of weeds. Currently, we are on a cutting-edge development.
  There are too many bugs in here, so we decided to start a new bug bounty program.
  For more information, please check out our security.txt!

  Made with love, by the *kawaii* PHP scientist.
-->
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta http-equiv="pragma" content="no-cache" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
	<title>ドンマイ ゲームショップ</title>
    <link href="static/kawaii.css" rel="stylesheet">
</head>
<body>
    <div id="background"></div>
	<div id="sticker"></div>
    <div id="namae"><h1>ドンマイ ゲームショップ</h1> Donmai GameShop &mdash; VR Game shop, just for Onii-chan!</div>
    <div id="copyright">IMAGE © 2017 IDEA FACTORY / COMPILE HEART. Best viewed with Chrome, 1920x1080.</div>
    <div id="menu">
<?php if($auth){ ?>
    <a href="?page=intro">Main</a>
    <a href="?page=products">Product</a>
    <a href="?page=logout">Logout</a>
<?php } ?>
    </div>
    <!-- コンテンツ -->
    <div class="body">
        <div class="container">
            <div style="background-color:rgba(0, 0, 0, 0.3); border: 1px dashed #fff; padding: 10px 0px;">
<?php

result:
    if($result){
        switch($result[0]){
            case "error":
                echo "<img src=./static/nep-nope.png width=128>";
                break;
            case "success":
                echo "<img src=./static/nep-congrat.png width=128>";
                break;
            case "sorry":
                echo "<img src=./static/nep-gomen.png width=128>";
                break;
            default:
                echo "<img src=./static/nep-yasumu.png width=128>";
                break;
        }
        echo "<h3>$result[1]</h3>";
        echo "<a href='#' onclick='history.go(-1);'>Home</a>";
        goto nop;
    }

    if($auth){
        // admin
        if((string)$session[0] === "neptune"){
            $encrypted_salt = bin2hex(openssl_encrypt(__SALT__, __CIPHER__, __SALT__, $options=OPENSSL_RAW_DATA, $iv=substr(__SALT__, 0, 16)));
            $result = ['yasumu', 'Wow, you\'re almost getting there. ﾚ(◣益◢#)ﾍ Here is your encrypted SALT-chan. (<a target="_new" href="http://e-shuushuu.net/images/2018-01-04-943519.png">?</a>) </h1>' . $encrypted_salt . '<hr>'];
            goto result;
        }
        $page = $_GET['page'];
        switch($page){
            case "products":
                $uzume = new Uzume('');

                //  http://php.net/manual/kr/function.array-rand.php#112227
                $video = ['qnX2CdOBcDI', 'BuU2bocSfDo', '4Bh1nm7Ir8c', 'xFdDNrd6W9s', 'nTOckbE6BTQ', 'eJeeAoKkcC0'];
                $video = $video[mt_rand(0, count($video) - 1)];
                $exist = false;
                if($_GET['id']){
                    $data = $uzume->read($_GET['id']);
                    if($data){
                        $exist = true;
                    }
                }
?>
<style>
th {
    background-color: #4CAF50;
    color: white;
}
</style>
<div style="display:inline-block;">
    <div style="display:inline-block;">
        <iframe noframeborder src="https://www.youtube.com/embed/<?=$video;?>?autoplay=1" width=400 style="border:0; height:300px;"></iframe>
    </div>
    <style> tr:hover { background:#ccc; cursor:pointer; } // monkey patch rocks! </style>
    <div style="display:inline-block; width:300px;">
<?php
    if(!$exist){
?>
        <table style="width:100%; border: 1px solid #9a8111">
            <thead>
               <tr><th>#</th><th>Item</th><th>Date</th></tr>
            </thead>
            <tbody>
<?php
        $list = $uzume->list();
        $i = 1;
        foreach($list as $product){
?>
            <tr onclick="location='?page=products&id=<?php echo $i; ?>'"><td><?php echo $i; ?></td><td><?php echo $product['title']; ?></td><td><?php echo $product['date']; ?></td></tr>
<?php
            $i++;
        }
    }else{
        echo "<table style='width:300px; background-color: #eee;'>";
        foreach($data as $key => $val){
?><tr><th><?php echo $key; ?></th><td><?php echo $val; ?></td></tr>
<?php
        }
    }
?>
            </tbody>
        </table>
    </div>
<?php
                break;
            case "logout":
                echo '<meta http-equiv="refresh" content="5;url=http://compileheart.com/neptune/v2r/" />';
                $result = $nep->bye();
                goto result;
                break;
            case "intro":
            default:
?>
    <marquee scrollamount="30" style="font-size: 30px; color:#fff;">ああああああああああああえええええええええいいいいいいいいいいいいいいいおおおおおおおおおおううううううううううううううううううう!!!!!!!!!!!</marquee>
    <iframe id="ytplayer" type="text/html" width="640" height="360" src="https://www.youtube.com/embed/h2i_qODoMxo?autoplay=1" frameborder="0"/></iframe><br>
    <h2 style="font-size: 24pt; color:#400;">ᕙ( ︡’︡益’︠)ง フラグはどこにもありません. (҂⌣̀_⌣́)</h2>
    <!-- Flag is not here -->
<?php
                break;
        }
    }else{
?>
                <iframe id="logo_frame" noframeborder src="https://www.youtube.com/embed/53oJdX0SRz0?list=PLxELUbyKLQEXgeY-f_6K2RUxH8IcsSdRT&index=0&autoplay=1"></iframe><br><br>
                <form method=POST>
                    <input type="text" id="username" name="username" placeholder="Username" class="form-control">
                    <input type="password" name="password" placeholder="Password" class="form-control">
                    <input type="submit" name="sign" value="Login">
                    <input type="submit" name="reg" value="Register">
                </form>
<?php
    }

nop: ?>
            </div>
        </div>
    </div>
</html>
<?php
    exit; phpinfo(1);
    exit; phpinfo(3);
    exit; phpinfo(3);
    exit; phpinfo(7);
?>
