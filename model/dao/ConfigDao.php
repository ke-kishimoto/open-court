<?php

namespace dao;
require_once(dirname(__FILE__).'/OpenCourtPDO.php');

use dao\OpenCourtPDO;
use PDO;
use entity\Config;

class ConfigDao {

    public function getConfig($id) {
        $pdo = new OpenCourtPDO();
        $sql = 'select * from config where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function update(Config $config) {
        $pdo = new OpenCourtPDO();
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