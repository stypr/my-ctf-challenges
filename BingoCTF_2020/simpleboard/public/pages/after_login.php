<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous" defer></script>
        <title>SimpleBoard</title>
    </head>
    <body>
    <div class="container">
        <div>
            <div style="float:left;"><p align=left><h3>Simpleboard</h3>Anonymous Internal Board</p></div>
            <div style="float:right;">Welcome <?php echo $_SESSION['username']; ?>!&nbsp;&nbsp;&nbsp;<a href="?logout" class="btn btn-danger">Logout</a></div>
        </div>
        <table class="table table-bordered table-hover" id="read" style="display:none;">
            <tr>
                <td width=30 class="bg-info text-white"><b>No.</b></td>
                <td id="read_no"></td>
                <td width=30 class="bg-info text-white"><b>Author</b></td>
                <td id="read_author"></td>
            </tr>
            <tr>
                <td width=100 class="bg-info text-white"><b>Title</b></td>
                <td colspan=3 id="read_title"></td>
            </tr>
            <tr>
                <td width=100 class="bg-info text-white"><b>Content</b></td>
                <td colspan=3 id="read_content"></td>
            </tr>
        </table>
        <form id="boardForm" name="boardForm" method="post">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Title</th>
                        <th>Author</th>
                    </tr>
                </thead>
                <tbody id="board_list">
                </tbody>
            </table>
            <div style="float:right;">
                <a href='#' onclick='toggle_write()' class="btn btn-success">Write</a>
            </div>
        </form>
        <table class="table table-bordered table-hover" id="write" style="display:none;">
            <tr>
                <td width=30 class="bg-success text-white"><b>Author</b></td>
                <td><input class="form-control" disabled value="<?php echo $_SESSION['username']; ?>"></td>
            </tr>
            <tr>
                <td width=100 class="bg-success text-white"><b>Title</b></td>
                <td colspan=3><input class="form-control" id="write_title"></td>
            </tr>
            <tr>
                <td width=100 class="bg-success text-white"><b>Content</b></td>
                <td colspan=3><textarea class="form-control" id="write_content" style="height:300px;"></textarea></td>
            </tr>
            <tr>
                <td colspan=4>
                    <a href='#' onclick='write_data()' class="btn btn-warning" style="width:100%;">Write to board</a>
                </td>
            </tr>
        </table>
        <script>
            function toggle_write(){
                if($("#write").css("display") == "none"){
                    $("#write").show();
                }else{
                    $("#write").hide();
                }
            }
            function write_data(){
                $.post("/?write", {"title": $("#write_title").val(), "content": $("#write_content").val()}, function(e){
                    if(e == "done"){
                        alert("Write success!");
                        location.reload();
                    }else if(e == "xss blocked"){
                        alert("XSS is not allowed :)");
                    }
                });
            }
            function read(post_id){
                $.post("/?read", {"id": post_id}, function(e){
                    if(!e){ alert('Content Deleted.'); location.reload(); };
                    $("#read").show();
                    $("#read_no").html(e[0]['_id']);
                    $("#read_title").html(e[0]['title']);
                    $("#read_author").html(e[0]['author']);
                    $("#read_content").html(e[0]['content']);
                });
            };
            $(document).ready(function(){
                $.get("/?list", function(r){
                    //console.log(r);
                    r.sort((a,b) => (a._id < b._id) ? 1: -1);
                    r.forEach(function(e){
                        $("#board_list").append(`
                        <tr onclick="read(${e['_id']})"><td>${e['_id']}</td><td>${e['title']}</td><td>${e['author']}</td></tr>
                        `);
                        console.log(e);
                    });
                });
            });
    </script>
    </body>
</html>
