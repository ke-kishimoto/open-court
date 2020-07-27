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
    <div><a class="logo" href="./index.php"><?php echo htmlspecialchars($_SESSION['system_title']) ?></a></div>
    <hr>
</header>