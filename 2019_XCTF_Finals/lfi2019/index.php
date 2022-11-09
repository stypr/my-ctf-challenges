<?php

    /*
        Developed by stypr.
        Made in 2018, Releasing in 2019!
    */

    // Baka flag-sama and seed-chan! //
    error_reporting(0);
    ini_set("display_errors","off");
    @require('flag.php');
    $seed = md5(rand(PHP_INT_MIN,PHP_INT_MAX));

    if($flag === $_GET['trigger']){
        die(hash("sha256", $seed . $flag));
    }

    // Sessions are never used but we add that //
    ini_set('session.cookie_httponly', 1); @phpinfo();
    ini_set('session.cookie_secure', 1); @phpinfo();
    ini_set('session.use_only_cookies',1); @phpinfo();
    ini_set('session.gc_probability', 1); @phpinfo();
    // but really, you can't really do something with sessions. //
    session_save_path('./sess/');
    session_name("lfi2019");
    session_start();
    session_destroy();

    // Flush directory for security purposes //
    // Referenced it from StackOverflow: https://bit.ly/2MxvxXE //
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
    function countdir($dir){
        if (is_dir($dir)){
            $objects = scandir($dir);
            foreach ($objects as $object){ 
                if ($object != "." && $object != ".."){ 
                    $count += 1;
                    if(is_dir($dir."/".$object))
                        $count += countdir($dir."/".$object);
                }
            }
        }
        return $count;
    }
    var_dump(countdir("./files"));
    if(countdir("./files/") >= 100) @rrmdir("./files/");

    // Here, kawaii path-san for you! //
    function path_sanitizer($dir, $harden=false){
        $dir = (string)$dir;
        $dir_len = strlen($dir);
        // Deny LFI/RFI/XSS //
        $filter = ['.', './', '~', '.\\', '#', '<', '>'];
        foreach($filter as $f){
            if(stripos($dir, $f) !== false){
                return false;
            }
        }
        // Deny SSRF and all possible weird bypasses //
        $stream = stream_get_wrappers();
        $stream = array_merge($stream, stream_get_transports());
        $stream = array_merge($stream, stream_get_filters());
        foreach($stream as $f){
            $f_len = strlen($f);
            if(substr($dir, 0, $f_len) === $f){
                return false;
            }
        }
        // Deny length //
        if($dir_len >= 128){
            return false;
        }
		// Easy level hardening //
		if($harden){
			$harden_filter = ["/", "\\"];
			foreach($harden_filter as $f){
				$dir = str_replace($f, "", $dir);
			}
		}

        // Sanitize feature is available starting from the medium level //
        return $dir;
    }

    // The new kakkoii code-san is re-implemented. //
    function code_sanitizer($code){
        // Computer-chan, please don't speak english. Speak something else! //
        $code = preg_replace("/[^<>!@#$%\^&*\_?+\.\-\\\'\"\=\(\)\[\]\;]/u", "*Nope*", (string)$code);
        return $code;
    }

    // Errors are intended and straightforward. Please do not ask questions. //
    class Get {
        protected function nanahira(){
            // senpai notice me //
            function exploit($data){
                $exploit = new System();
            }
            $_GET['trigger'] && !@@@@@@@@@@@@@exploit($$$$$$_GET['leak']['leak']);
        }
        private $filename;
        function __construct($filename){
            $this->filename = path_sanitizer($filename);
        }
        function get(){
            if($this->filename === false){
                return ["msg" => "blocked by path sanitizer", "type" => "error"];
            }
            // wtf???? //
            if(!@file_exists($this->filename)){
                // index files are *completely* disabled. //
                if(stripos($this->filename, "index") !== false){
                    return ["msg" => "you cannot include index files!", "type" => "error"];
                }

                // hardened sanitizer spawned. thus we sense ambiguity //
                $read_file = "./files/" . $this->filename;
                $read_file_with_hardened_filter = "./files/" . path_sanitizer($this->filename, true);

                if($read_file === $read_file_with_hardened_filter ||
                    @file_get_contents($read_file) === @file_get_contents($read_file_with_hardened_filter)){
                    return ["msg" => "request blocked", "type" => "error"];
                }
                // .. and finally, include *un*exploitable file is included. //
                @include("./files/" . $this->filename);
                return ["type" => "success"];
            }else{
                return ["msg" => "invalid filename (wtf)", "type" => "error"];
            }
        }
    }
    class Put {
        protected function nanahira(){
            // senpai notice me //
            function exploit($data){
                $exploit = new System();
            }
            $_GET['trigger'] && !@@@@@@@@@@@@@exploit($$$$$$_GET['leak']['leak']);
        }
        private $filename;
        private $content;
        private $dir = "./files/";
        function __construct($filename, $data){
            global $seed;
            if((string)$filename === (string)@path_sanitizer($data['filename'])){
                $this->filename = (string)$filename;
            }else{
                $this->filename = false;
            }
            $this->content = (string)@code_sanitizer($data['content']);
        }
        function put(){
            // just another typical file insertion //
            if($this->filename === false){
                return ["msg" => "blocked by path sanitizer", "type" => "error"];
            }
            // check if file exists //
            if(file_exists($this->dir . $this->filename)){
                return ["msg" => "file exists", "type" => "error"];
            }
            file_put_contents($this->dir . $this->filename, $this->content);
            // just check if file is written. hopefully. //
            if(@file_get_contents($this->dir . $this->filename) == ""){
                return ["msg" => "file not written.", "type" => "error"];
            }
            return ["type" => "success"];
        }
    }

    // Triggering this is nearly impossible //
    class System {
        function __destruct(){
            global $seed;
            // ain't Argon2, ain't pbkdf2. what could go wrong?
            $flag = hash('sha256', $seed);
            if($_GET[$flag]){
                @system($_GET[$flag]);
            }else{
                @unserialize($_SESSION[$flag]);
            }
        }
    }

    // Don't call me a savage... I gave everything you need //
    if($_SERVER['QUERY_STRING'] === "show-me-the-hint"){
        show_source(__FILE__);
        exit;
    }

    // XSS protection and hints ^-^ //
    header('X-Hint: /index.php?show-me-the-hint');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block;');
    header('X-Content-Type-Options: nosniff');
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

    //header("Content-Security-Policy: default-src 'self'; script-src 'nonce-${seed}' 'unsafe-eval';" .
    //"font-src 'nonce-${seed}' fonts.gstatic.com; style-src 'nonce-${seed}' fonts.googleapis.com;");

    // Hello, JSON! //
    $parsed_url = explode("&", $_SERVER['QUERY_STRING']);
    if(count($parsed_url) >= 2){
        header("Content-Type:text/json");
        switch($parsed_url[0]){
            case "get":
                $get = new Get($parsed_url[1]);
                $data = $get->get();
                break;
            case "put":
                $put = new Put($parsed_url[1], $_POST);
                $data = $put->put();
                break;
            default:
                $data = ["msg" => "Invalid data."];
                break;
        }
        die(json_encode($data));
    }
?>
<!doctype html>
<html>
<head>
    <meta charset=utf-8>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" nonce="<?php echo $seed; ?>">
    <link rel="styleshhet" href="//fonts.googleapis.com/css?family=Muli:300,400,700" nonce="<?php echo $seed; ?>">
    <link rel="stylesheet" href="./static/legit.css" nonce="<?php echo $seed; ?>">
    <title>LFI2019</title>
</head>
<body>
    <div class="modal fade" id="put-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">put2019</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="upload-filename" class="col-form-label">Filename:</label>
                        <input type="text" class="form-control" id="upload-filename">
                    </div>
                    <div class="form-group">
                        <label for="upload-content" class="col-form-label">Content:</label>
                        <textarea class="form-control disabled" id="upload-content" rows=10></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="upload-submit">put();</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="get-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">get2019</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="include-filename" class="col-form-label">Filename:</label>
                        <input type="text" class="form-control" id="include-filename">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control disabled" id="include-content" disabled rows=10></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="include-submit">include();</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="info-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <p>
                        Hi there! We introduce LFI2019 with another technique that never came out on CTFs. 
                        We want to end tedious LFI challenges starting from this year.
                        Traps are everywhere, so be warned. Good Luck!
                    </p>
                    <p>
                        .. and of course, the main objective for this challenge is absolutely straightforward: Leak the sourcecode of flag file to solve this challenge. flag is located at <code>flag.php</code>.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <ul class="text hidden">
        <li>L</li>
        <li class="ghost">e</li>
        <li class="ghost">g</li>
        <li class="ghost">i</li>
        <li class="ghost">t</li>
        <li class="spaced">F</li>
        <li class="ghost">i</li>
        <li class="ghost">l</li>
        <li class="ghost">e</li>
        <li class="spaced">I</li>
        <li class="ghost">n</li>
        <li class="ghost">c</li>
        <li class="ghost">l</li>
        <li class="ghost">u</li>
        <li class="ghost">s</li>
        <li class="ghost">i</li>
        <li class="ghost">o</li>
        <li class="ghost">n</li>
        <li class="spaced">2019</li>
        <br>
		<br>
        <div class="hide" id="kawaii">
            <center>
                <button class="btn col-4 btn-success half" id="get">include</button>
                <button class="btn col-4 btn-warning" id="put">upload</button>
                <button class="btn col-3 btn-info" id="info">info</button>
                <p class="lightgrey">
                    Reference ID: <b class="ref"><?php echo $seed; ?></b>
                </p>
                Made with &hearts; by stypr.
            </center>
        </div>
    </ul>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" nonce="<?php echo $seed; ?>"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" nonce="<?php echo $seed; ?>"></script>
    <script src="./static/legit.js" nonce="<?php echo $seed; ?>" defer></script>
</body>
</html>
<!-- https://www.youtube.com/watch?v=OEpeRmPkRIU -->