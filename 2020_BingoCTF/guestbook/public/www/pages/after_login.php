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
            <div class="float-right">Welcome <?php echo $_SESSION['username']; ?>!&nbsp;&nbsp;&nbsp;<a href="?logout" class="btn btn-danger">Logout</a></div>
        </div>
        <table class="table" id="question-box">
            <tr>
                <td width=30 class="bg-info text-white"><b>Question</b></td>
                <td class="bg-info text-white"><textarea class="form-control" id="question"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="button" class="btn btn-info float-right" value="Ask!" id="ask-button"></b></td>
            </tr>
        </table>
        <form id="boardForm" name="boardForm" method="post">
            <table class="table table-striped table-hover">
                <tbody id="board_list">
                </tbody>
            </table>
        </form>
        <script nonce="script">
            function ask_question(){
                $.post("/?ask", {"question": $("#question").val()}, function(e){
                    if(e == "done"){
                        alert("Write success!");
                        location.reload();
                    }else if(e == "xss blocked"){
                        alert("XSS is not allowed :)");
                    }
                });
            }
            function list_question(post_id){
                $.post("/?", {"id": post_id}, function(e){
                    if(!e){ alert('Content Deleted.'); location.reload(); };
                    $("#read").show();
                    $("#read_no").html(e[0]['_id']);
                    $("#read_title").html(e[0]['title']);
                    $("#read_author").html(e[0]['author']);
                    $("#read_content").html(e[0]['content']);
                });
            };
            $(document).ready(function(){
                $("#ask-button").click(function(){ ask_question(); });
                $.get("/?list", function(r){
                    //console.log(r);
                    r.sort((a,b) => (a._id < b._id) ? 1: -1);
                    r.forEach(function(e){
                        $("#board_list").append(`
                        <tr><td><textarea class="form-control" disabled>${e['question']}</textarea></td></tr>
                        ${(e['comment'])? `<tr><td>${e['comment']}</td></tr>`:""}
                        `);
                        console.log(e);
                    });
                });
            });
    </script>
    </body>
</html>
