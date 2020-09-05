<header class="<?php echo $_SESSION['bgColor'] ?> " role="banner">
    <!-- ハンバーガーボタン -->
    <button type="button" class="drawer-toggle drawer-hamburger">
      <span class="sr-only">toggle navigation</span>
      <span class="drawer-hamburger-icon"></span>
    </button>
    <!-- ナビゲーションの中身 -->
    <nav class="drawer-nav" role="navigation">
      <ul class="drawer-menu">
        <li><a class="drawer-brand" href="#">メニュー</a></li>
        <li><a class="drawer-menu-item" href="/event/index">トップ</a></li>
        <li class="<?php echo htmlspecialchars($_SESSION['noLoginClass']) ?>"><a class="drawer-menu-item" href="/user/signup">新規作成</a></li>
        <li class="<?php echo htmlspecialchars($_SESSION['noLoginClass']) ?>"><a class="drawer-menu-item" href="/user/signin">ログイン</a></li>
        <li class="<?php echo htmlspecialchars($_SESSION['loginClass']) ?>"><a class="drawer-menu-item" href="/user/participatingEventList">参加イベント一覧</a></li>
        <li class="<?php echo htmlspecialchars($_SESSION['loginClass']) ?>"><a class="drawer-menu-item" href="/participant/eventBatchRegist">イベント一括参加</a></li>
        <li class="<?php echo htmlspecialchars($_SESSION['loginClass']) ?>"><a class="drawer-menu-item" href="/user/edit?id=<?php echo $_SESSION['user_id'] ?>">アカウント情報</a></li>
        <?php if($_SESSION['adminMenuFlg'] == '1'): ?>
          <li class="<?php echo htmlspecialchars($_SESSION['loginClass']) ?>"><a class="drawer-menu-item" href="/admin/admin/index?id=<?php echo $_SESSION['user_id'] ?>">管理者画面へ</a></li>
        <?php endif; ?>
        <li class="<?php echo htmlspecialchars($_SESSION['loginClass']) ?>"><a class="drawer-menu-item" href="/user/signout">ログアウト</a></li>
        <li class=""><a class="drawer-menu-item" href="inquiry/inquiry">お問い合わせ</a></li>
      </ul>
    </nav>

    <div class="system-header">
      <div class="dummy">
        <?php echo $_SESSION['user_name'] ?>さん</span>
      </div>
      <div class="system-name">
        <a class="logo" href="/event/index"><?php echo htmlspecialchars($_SESSION['system_title']) ?></a>
      </div>
      <div class="user-name">
          <span><?php echo $_SESSION['user_name']?>さん</span>
          <span class="participant-header-menu">
            <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($_SESSION['noLoginClass']) ?>" href="/user/signup" role="button" style="margin-right:5px;">新規登録</a>
            <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($_SESSION['noLoginClass']) ?>" href="/user/signin" role="button">ログイン</a>
            <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($_SESSION['loginClass']) ?>" href="/user/edit?id=<?php echo $_SESSION['user_id'] ?>" role="button" style="margin-right:5px;">アカウント情報</a>
            <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($_SESSION['loginClass']) ?>" href="/user/signout" role="button">ログアウト</a>
          </span>
      <div>
    </div>
    
</header>