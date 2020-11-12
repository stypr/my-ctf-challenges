<?php

$_GET['id'] = ($_GET['id']) ? (int)$_GET['id'] : "";
$result = list_guestbook_admin($_GET['id']);
$_GET['id'] = $result['_id'];

?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" nonce="script"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" nonce="script"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" nonce="script"></script>
        <title>Guestbook</title>
    </head>
    <body>
    <div class="container">
        <div>
            <div class="float-left"><p align=left><h3>Guestbook</h3>Ask admin about anything!</p></div>
            <div class="float-right">Welcome <?php echo $_SESSION['username']; ?></div>
        </div>
        <table class="table" id="question-box">
            <tr>
                <td width=30 class="bg-danger text-white"><b>Answer</b></td>
                <td class="bg-danger text-white"><input type="text" class="form-control" id="answer"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="button" class="btn btn-info float-right" value="Reply" id="answer-button"></b></td>
            </tr>
        </table>
        <form id="boardForm" name="boardForm" method="post">
            <table class="table table-striped table-hover">
                <tbody id="board_list">
                    <tr>
                        <td><textarea class="form-control" disabled><?php echo $result['question']; ?></textarea></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <script nonce="script">
            function answer_question(){
                // For reducing bugs/lags
                post_id = <?php echo $_GET['id']; ?>;
                $.post("/?comment", {"post_id": post_id, "comment": $("#answer").val()}, function(e){
                    console.log("DONE!");
                    location.href='/deadend';
                });
            };
            $(document).ready(function(){
                $("#answer-button").click(function(){ answer_question(); });
            });
        </script>
    </body>
</html>
