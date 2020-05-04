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
        $(document).ready(function() {
            
            var token = '<?= $_GET['token'] ?>',
            memory = '<?= $_GET['memory'] ?>',
            email = '<?= $_GET['email'] ?>';
            
            //console.log(email);
            $.ajax({
                type: 'POST',
                url: '/db/checkEmail',
                data: 'token='+token+'&key='+memory+'&email='+email,
                
                error: function(x, t, e) {
                    if (t === 'timeout') {
                        // Произошел тайм-аут
                    } else {
                        console.log('Ошибка: ' + e);
                    }
                },
                success: function(text) {
                    console.log(text);
                },
                timeout: 10000

            });
        });
        
    </script>
</body>

</html>