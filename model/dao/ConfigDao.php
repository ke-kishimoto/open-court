<?php

namespace dao;
require_once(dirname(__FILE__).'/DaoFactory.php');

use dao\DaoFactory;
use PDO;
use entity\Config;

class ConfigDao {

    public function getConfig($id) {
        $pdo = DaoFactory::getConnection();
        $sql = 'select * from config where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function update(Config $config) {
        $pdo = DaoFactory::getConnection();
        $sql = 'update config set 
        line_token = :line_token
        where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $config->id, PDO::PARAM_INT);
        $prepare->bindValue(':line_token', $config->lineToken, PDO::PARAM_STR);
        $prepare->execute();
    }
}

?>