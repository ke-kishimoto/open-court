<header class="<?php echo $bgColor ?> " role="banner">
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
        <li class="<?php echo htmlspecialchars($noLoginClass) ?>"><a class="drawer-menu-item" href="SignIn.php">ログイン</a></li>
        <li class="<?php echo htmlspecialchars($loginClass) ?>"><a class="drawer-menu-item" href="./ParticipatingEventList.php">参加イベント一覧</a></li>
        <li class="<?php echo htmlspecialchars($loginClass) ?>"><a class="drawer-menu-item" href="./EventBatchRegist.php">イベント一括参加</a></li>
        <li class="<?php echo htmlspecialchars($loginClass) ?>"><a class="drawer-menu-item" href="./SignUp.php?id=<?php echo $id ?>">アカウント情報</a></li>
        <?php if($adminMenuFlg == '1'): ?>
          <li class="<?php echo htmlspecialchars($loginClass) ?>"><a class="drawer-menu-item" href="./admin/index.php?id=<?php echo $id ?>">管理者画面へ</a></li>
        <?php endif; ?>
        <li class="<?php echo htmlspecialchars($loginClass) ?>"><a class="drawer-menu-item" href="./LogoutController.php">ログアウト</a></li>
        <li class=""><a class="drawer-menu-item" href="./Inquiry.php">お問い合わせ</a></li>
      </ul>
    </nav>

    <div class="system-header">
      <div class="dummy">
        <?php echo $userName ?>さん</span>
      </div>
      <div class="system-name">
        <a class="logo" href="./index.php"><?php echo htmlspecialchars($_SESSION['system_title']) ?></a>
      </div>
      <div class="user-name">
          <span><?php echo $userName ?>さん</span>
          <span class="participant-header-menu">
            <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($noLoginClass) ?>" href="./SignUp.php" role="button" style="margin-right:5px;">新規登録</a>
            <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($noLoginClass) ?>" href="./SignIn.php" role="button">ログイン</a>
            <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($loginClass) ?>" href="./SignUp.php?id=<?php echo $id ?>" role="button" style="margin-right:5px;">アカウント情報</a>
            <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($loginClass) ?>" href="./LogoutController.php" role="button">ログアウト</a>
          </span>
      <div>
    </div>
    
</header>