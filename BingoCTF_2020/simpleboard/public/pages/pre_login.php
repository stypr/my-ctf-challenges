<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous" defer></script>
        <title>SimpleBoard</title>
        <style>
        body {
          margin: 0;
          padding: 0;
          background-color: #17a2b8;
          height: 100vh;
        }
        #login .container #login-row #login-column #login-box {
          margin-top: 30px;
          max-width: 600px;
          height: 320px;
          border: 1px solid #9C9C9C;
          background-color: #EAEAEA;
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
        <h3 class="text-center text-white pt-5"><img src="/?image=6c6f676f.png"></h3>
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
                                            <input type="button" name="submit" class="btn btn-success btn-md" style="width:100%" value="Login" onclick="login()">
                                        </td>
                                        <td>
                                            <input type="button" name="submit" class="btn btn-warning btn-md" style="width:100%"value="Register" onclick="register()">
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
    <script>
        function register(){
            $.post("/?register", {"username": $("#username").val(), "password": $("#password").val()}, function(e){
                if(e=="done"){
                    login();
                }else if(e=="username already exists.."){
                    alert('Username already exists! Try another username');
                }else if(e=="too long"){
                    alert('Username or password is too long.');
                }
            });
        }
        function login(){
            $.post("/?login", {"username": $("#username").val(), "password": $("#password").val()}, function(e){
                if(e=="success"){
                    location.reload();
                }else if(e == "invalid user"){
                    alert('Wrong username or password');
                }else if(e == "too long"){
                    alert('Username or password is too long.');
                }
            });
        }
    </script>
</body>
</html>
