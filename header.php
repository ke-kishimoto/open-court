<?php 
require_once('./model/dao/ConfigDao.php');
use dao\ConfigDao;
if (!isset($_SESSION['system_title'])) {
    $configDao = new ConfigDao();
    $config = $configDao->getConfig(1);
    $_SESSION['system_title'] = $config['system_title'];
}

?>
<header>
    <div>
        <a class="logo" href="./index.php"><?php echo htmlspecialchars($_SESSION['system_title']) ?></a>
    </div>
    <div class="nav">
        <a class="btn btn-sm btn-outline-dark" href="signUp.php" role="button">新規登録</a>
        <a class="btn btn-sm btn-outline-dark" href="#" role="button">ログイン</a>
    </div>
</header>