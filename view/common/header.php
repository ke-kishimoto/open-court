<header class="<?php echo $bgColor ?> participant">
    <!-- ハンバーガーボタン -->
    <button type="button" class="drawer-toggle drawer-hamburger">
      <span class="sr-only">toggle navigation</span>
      <span class="drawer-hamburger-icon"></span>
    </button>
    <!-- ナビゲーションの中身 -->
    <nav class="drawer-nav" role="navigation">
      <ul class="drawer-menu">
        <li><a class="drawer-brand" href="#">メニュー</a></li>
        <li><a class="drawer-menu-item" href="index.php">トップ</a></li>
        <li class="<?php echo htmlspecialchars($noLoginClass) ?>"><a class="drawer-menu-item" href="SignUp.php">新規作成</a></li>
        <li class="<?php echo htmlspecialchars($noLoginClass) ?>"><a class="drawer-menu-item" href="SignUp.php">ログイン</a></li>
        <li class="<?php echo htmlspecialchars($loginClass) ?>"><a class="drawer-menu-item" href="./ParticipatingEventList.php">参加イベント一覧</a></li>
        <li class="<?php echo htmlspecialchars($loginClass) ?>"><a class="drawer-menu-item" href="./SignUp.php?id=<?php echo $id ?>">アカウント情報</a></li>
        <li class="<?php echo htmlspecialchars($loginClass) ?>"><a class="drawer-menu-item" href="LououtController.php">ログアウト</a></li>
      </ul>
    </nav>

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