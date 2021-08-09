<header id="header" class="<?php echo $_SESSION['bgColor'] ?>" role="banner">
     <!-- ハンバーガーボタン -->
     <button type="button" class="drawer-toggle drawer-hamburger">
      <span class="sr-only">toggle navigation</span>
      <span class="drawer-hamburger-icon"></span>
    </button>
    <!-- ナビゲーションの中身 -->
    <nav class="drawer-nav" role="navigation">
      <ul class="drawer-menu">
        <li><a class="drawer-brand" href="#">管理者メニュー</a></li>
        <li><a class="drawer-menu-item" href="/admin/index">トップ</a></li>
        <li><a class="drawer-menu-item" href="/admin/eventInfo">新規イベント登録</a></li>
        <li><a class="drawer-menu-item" href="/admin/eventTemplate">テンプレート設定</a></li>
        <li><a class="drawer-menu-item" href="/admin/userList">ユーザーリスト</a></li>
        <li><a class="drawer-menu-item" href="/admin/config">システム設定</a></li>
        <li><a class="drawer-menu-item" href="/admin/inquiryList">問い合わせ一覧</a></li>
        <li><a class="drawer-menu-item" href="/admin/notice">お知らせ登録</a></li>
        <li><a class="drawer-menu-item" href="/sales/index">売上管理</a></li>
        <li><a class="drawer-menu-item" href="/admin/delivery/index">セグメント配信</a></li>
        <li><a class="drawer-menu-item" href="/">一般利用者画面へ</a></li>
        <li><a class="drawer-menu-item" href="/user/signout">ログアウト</a></li>
      </ul>
    </nav>
    <div class="system-header">
      <div class="dummy">
        <?php echo $_SESSION['user_name'] ?>さん</span>
      </div>
      <div class="system-name">
        <a class="logo" href="/admin/index"><?php echo $_SESSION['system_title'] ?></a>
      </div>
      <div class="user-name">
        <span id="user_name" class="user-name"><?php echo $_SESSION['user_name'] ?>さん</span>
      </div>
    </div>
</header>