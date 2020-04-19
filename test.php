<?php
require_once 'App/AuthUser.php';

$auth = new \App\AuthUser();
$tokens = $auth->getToken();
$memory = $tokens['random'];
$token = $tokens['cash'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="app"></div>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script>
        $.ajax({
            url:'http://legacy.loc/chechk/auth-email',
            type: 'post',
            data:'token=<?=$token?>&memory=<?=$memory?>',
            success:function(text){
                $("#app").html(text);
            },
            error:function(){
                console.log("error");
            }
        })
    </script>
</body>
</html>