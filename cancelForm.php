<?php
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
    <form action="cancel.php" method="post">
        <input type="hidden" name="game_id" value="<?php echo $_GET['gameid'] ?>">
    <p>
        登録時のメールアドレスを入力してください。
        <input class="form-control" type="email" name="email" required>
    </p>
        <button id="btn-cancel" class="btn btn-primary" type="submit">参加キャンセル</button>
    </form>
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        'use strict';
        $(function(){ 
            $('#btn-cancel').on('click', function() {
                return confirm('参加をキャンセルしてもよろしいですか');
            });
        })
    </script> -->
</body>
</html>