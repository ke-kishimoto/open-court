<?php
if(isset($_SESSION['user'])) {
    $email = $_SESSION['user']['email'];
    $mode = 'login';
} else {
    $email = '';
    $mode = 'guest';
}

if(isset($_SESSION['errMsg'])) {
    $errMsg = $_SESSION['errMsg'];
    unset($_SESSION['errMsg']);
} else {
    $errMsg = '';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>予約キャンセル</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
    <?php include('./header.php') ?>
    <form action="cancelComplete.php" method="post">
        <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($_GET['gameid']) ?>">
        <input type="hidden" name="mode" id="mode" value="<?php echo $mode ?>">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <p>
            メールアドレス
            <input class="form-control" type="email" name="email" required value="<?php echo $email ?>">
        </p>
        <div id="password-area">
            パスワード
            <input class="form-control" type="password" name="password" required>
        </div>
        <button id="btn-cancel" class="btn btn-primary" type="submit">参加キャンセル</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        'use strict';
        $(function(){ 
            if($('#mode').val() === 'guest') {
                $('#password-area').remove();
            }
        })
    </script>
</body>
</html>