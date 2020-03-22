<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>設定変更</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="container">
<form action="register.php" method="post" class="form-group">
    <p>
        LINEトークン<input class="form-control" type="text" name="linetoken"  required value="<?php echo 'a' ?>">
    </p>
    <p>
        <button class="btn btn-primary" type="submit" name="register">登録</button>
    </p>
</form>
</body>
</html>