<?php 
require_once('./model/dao/ConfigDao.php');
use dao\ConfigDao;
if (!isset($_SESSION['system_title'])) {
    $configDao = new ConfigDao();
    $config = $configDao->getConfig(1);
    $_SESSION['system_title'] = $config['system_title'];
}

if(isset($_SESSION['user']) == null) {
    $loginClass = 'hidden';
    $noLoginClass = '';
} else {
    $loginClass = '';
    $noLoginClass = 'hidden';
}

?>
<header>
    <div>
        <a class="logo" href="./index.php"><?php echo htmlspecialchars($_SESSION['system_title']) ?></a>
    </div>
    
    <div class="nav">
        <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($noLoginClass) ?>" href="signUp.php" role="button">新規登録</a>
        <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($noLoginClass) ?>" href="signIn.php" role="button">ログイン</a>
        <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($loginClass) ?>" href="signIn.php" role="button">アカウント情報</a>
        <a class="btn btn-sm btn-outline-dark <?php echo htmlspecialchars($loginClass) ?>" href="./controller/LogoutController.php" role="button">ログアウト</a>
    </div>
    
</header>