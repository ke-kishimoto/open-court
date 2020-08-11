<header class="<?php echo $bgColor ?> participant">
    <div>
        <a class="logo" href="./index.php"><?php echo htmlspecialchars($_SESSION['system_title']) ?></a>
    </div>
    
    <div class="nav">
        <span id="user_name" class="user-name"><?php echo $userName ?>さん</span>
        <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($noLoginClass) ?>" href="./SignUp.php" role="button" style="margin-right:5px;">新規登録</a>
        <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($noLoginClass) ?>" href="./SignIn.php" role="button">ログイン</a>
        <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($loginClass) ?>" href="./SignUp.php?id=<?php echo $id ?>" role="button" style="margin-right:5px;">アカウント情報</a>
        <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($loginClass) ?>" href="./LogoutController.php" role="button">ログアウト</a>
    </div>
    
</header>