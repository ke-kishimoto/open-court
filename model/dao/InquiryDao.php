<?php

namespace dao;
require_once(dirname(__FILE__).'/DaoFactory.php');
use dao\DaoFactory;

use PDO;
use entity\Inquiry;

class InquiryDao {
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

    public function insert(Inquiry $inquiry) {
        $sql = 'insert into inquiry (game_id, name, email, content, status_flg, register_date, update_date)
        values (:game_id, :name, :email, :content, :status_flg, :register_date, :update_date)';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':game_id', $inquiry->gameId, PDO::PARAM_INT);
        $prepare->bindValue(':name', $inquiry->name, PDO::PARAM_STR);
        $prepare->bindValue(':email', $inquiry->email, PDO::PARAM_STR);
        $prepare->bindValue(':content', $inquiry->content, PDO::PARAM_STR);
        $prepare->bindValue(':status_flg', $inquiry->statusFlg, PDO::PARAM_INT);
        $prepare->bindValue(':register_date', $inquiry->registerDate, PDO::PARAM_STR);
        $prepare->bindValue(':update_date', $inquiry->updateDate, PDO::PARAM_STR);
        $prepare->execute();
    }
}

