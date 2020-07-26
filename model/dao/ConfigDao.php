<?php

namespace dao;
require_once(dirname(__FILE__).'/DaoFactory.php');

use dao\DaoFactory;
use PDO;
use entity\Config;

class ConfigDao {

    private static $systemTitle;
    public static function getSystemTitle() {
        return ConfigDao::$systemTitle;
    }
    public static function setSystemTitle(string $systemTitle) {
        ConfigDao::$systemTitle = $systemTitle;
    }

    private $pdo;
    public function __construct() {
        $this->pdo = DaoFactory::getConnection();
    }
    public function getPdo() {
        return $this->pdo;
    }
    public function setPdo(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getConfig($id) {
        $sql = 'select * from config where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function update(Config $config) {
        $sql = 'update config set 
        line_token = :line_token
        , system_title = :system_title
        where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $config->id, PDO::PARAM_INT);
        $prepare->bindValue(':line_token', $config->lineToken, PDO::PARAM_STR);
        $prepare->bindValue(':system_title', $config->systemTitle, PDO::PARAM_STR);
        $prepare->execute();
        $_SESSION['system_title'] = $config->systemTitle;
    }
}

?>