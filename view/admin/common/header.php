<header class="<?php echo $bgColor ?> admin" role="banner">
     <!-- ハンバーガーボタン -->
     <button type="button" class="drawer-toggle drawer-hamburger">
      <span class="sr-only">toggle navigation</span>
      <span class="drawer-hamburger-icon"></span>
    </button>
    <!-- ナビゲーションの中身 -->
    <nav class="drawer-nav" role="navigation">
      <ul class="drawer-menu">
        <li><a class="drawer-brand" href="#">管理者メニュー</a></li>
        <li><a class="drawer-menu-item" href="index.php">トップ</a></li>
        <li><a class="drawer-menu-item" href="EventInfo.php">新規イベント登録</a></li>
        <li><a class="drawer-menu-item" href="EventTemplate.php">テンプレート設定</a></li>
        <li><a class="drawer-menu-item" href="UserList.php">ユーザーリスト</a></li>
        <li><a class="drawer-menu-item" href="Config.php">システム設定</a></li>
      </ul>
    </nav>
    <div class="system-title"><a class="logo" href="./index.php"><?php echo $_SESSION['system_title'] ?></a></div>
    <hr>
</header>