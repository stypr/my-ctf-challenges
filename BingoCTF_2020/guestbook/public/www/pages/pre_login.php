<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" nonce="script"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" nonce="script"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" nonce="script"></script>
        <title>Guestbook</title>
        <style nonce="script">
        body {
          margin: 0;
          padding: 0;
          background-color: #b8a217;
          height: 100vh;
        }
        #login .container #login-row #login-column #login-box {
          margin-top: 30px;
          max-width: 600px;
          height: 320px;
          border: 1px solid #9C9C9C;
          background-color: #ededed;
        }
        #login .container #login-row #login-column #login-box #login-form {
          padding: 20px;
        }
        #login .container #login-row #login-column #login-box #login-form #register-link {
          margin-top: -35px;
        }
        </style>
    </head>
<body>
    <div id="login">
        <h3 class="text-center text-white pt-5">Guestbook</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="" method="post">
                            <h3 class="text-center text-info">Login</h3>
                            <div class="form-group">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <table width=100%>
                                    <tr>
                                        <td width=50%>
                                            <input type="button" name="submit" class="btn btn-success btn-md col-md-12" value="Login" id="login-button">
                                        </td>
                                        <td>
                                            <input type="button" name="submit" class="btn btn-warning btn-md col-md-12" value="Register" id="register-button">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script nonce="script">
        function register(){
            $.post("/?register", {"username": $("#username").val(), "password": $("#password").val()}, function(e){
                if(e=="done"){
                    login();
                }else if(e=="username already exists.."){
                    alert('Username already exists! Try another username');
                }else if(e=="too long or too short"){
                    alert('Username or password is too long or too short.');
                }
            });
        }
        function login(){
            $.post("/?login", {"username": $("#username").val(), "password": $("#password").val()}, function(e){
                if(e=="success"){
                    location.reload();
                }else if(e == "invalid user"){
                    alert('Wrong username or password');
                }else if(e=="too long or too short"){
                    alert('Username or password is too long.');
                }
            });
        }
        $('document').ready(function(){
            $("#login-button").click(function(){ login() });
            $("#register-button").click(function(){ register() });
        });
    </script>
</body>
</html>
