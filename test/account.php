<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запись аккаунта</title>
</head>
<body>
    <form method="post" action="/db/setAccount">
    <input type="text" name="first_name">
    <input type="text" name="last_name">
    <input type="text" name="phone">
    <input type="hidden" name="id_account" value="22">
    <input type="submit">
    </form>
</body>
</html>