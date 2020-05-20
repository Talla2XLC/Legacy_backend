<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POST</title>
</head>
<body>

<script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
<div id="info"></div>
<form action="/ddd" method="post">
<input type="text" id="album_name" name="album_name">
<a href="#" id="click">Отправить</a>

<script>
    $(document).ready(function(){
        $("#click").click(function(){
            var album = $("#album_name").val();
            console.log(album);
            $.ajax({
                type:'post',
                url:'http://legacy.loc/db/getUsers/all',
                data:'name_album='+album,
                success:function(info){
                    $("#info").text(info);
                },
                error:function(){
                    console.log('error');
                }
            });
        });
    });
</script>
</body>
</html>