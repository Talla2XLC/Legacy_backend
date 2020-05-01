<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>check email user</title>
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script>
        $(document).ready(function(){
            var token = '<?=$_GET['token']?>',
            memory = '<?=$_GET['memory']?>',
            email = '<?=$_GET['email']?>';
            $.ajax({
                type:'post',
                url:'/db/checkEmail',
                data:'token=' + token + '&memory=' + memory + '&email=' + email,
                success:function(text){
                    consol.log(text);
                }
            })
        });
    </script>
</body>
</html>