<?php
require_once(dirname(__FILE__).'/model/entity/Users.php');
require_once(dirname(__FILE__).'/model/dao/UsersDao.php');
use entity\Users;
use dao\UsersDao;

$limitFlg = false;
$btnClass = 'btn btn-primary';
$btnLiteral = '登録';

?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>パスワード変更</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
<?php include('./header.php') ?>

<div>
    <form id="signUp_form" action="passwordChangeComplete.php" method="post" class="form-group">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <div id="password-area">
            <p>
                パスワード
                <input class="form-control" type="password" name="password" required maxlength="50">
            </p>
            <p>
                パスワード(再入力)
                <input class="form-control" type="password" name="rePassword" required maxlength="50">
            </p>
        </div>
        <button class="<?php echo htmlspecialchars($btnClass) ?>" type="submit"><?php echo htmlspecialchars($btnLiteral) ?></button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    
</script>
</body>
</html>