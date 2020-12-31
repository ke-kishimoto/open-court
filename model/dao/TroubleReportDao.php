<?php

namespace dao;

use dao\DaoFactory;
use PDO;
use entity\TroubleReport;

class TroubleReportDao
{
    private $pdo;
    public function __construct() 
    {
        $this->pdo = DaoFactory::getConnection();
    }
    public function getPdo() 
    {
        return $this->pdo;
    }
    public function setPdo(PDO $pdo) 
    {
        $this->pdo = $pdo;
    }

    public function getTroubleReport($id)
    {
        $sql = "select * from troubleReport where id = :id";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetch();
    }

    public function insert(TroubleReport $troubleReport)
    {
        $sql = "insert into troubleReport 
        (
            name
            , title
            , content
            , status_flg
            , register_date
        )
        values
        (
            :name
            , :title
            , :content
            , :status_flg
            , :register_date
        )";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':name', $troubleReport->name, PDO::PARAM_STR);
        $prepare->bindValue(':title', $troubleReport->title, PDO::PARAM_STR);
        $prepare->bindValue(':content', $troubleReport->content, PDO::PARAM_STR);
        $prepare->bindValue(':status_flg', $troubleReport->statusFlg, PDO::PARAM_STR);
        $prepare->bindValue(':register_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->execute();
    }

    public function update(TroubleReport $troubleReport)
    {
        $sql = "update troubleReport set
        name = :name
        , title = :titel
        , content = :content
        , status_flg = :status_flg
        , update_date = :update_date";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':name', $troubleReport->name, PDO::PARAM_STR);
        $prepare->bindValue(':title', $troubleReport->title, PDO::PARAM_STR);
        $prepare->bindValue(':content', $troubleReport->content, PDO::PARAM_STR);
        $prepare->bindValue(':status_flg', $troubleReport->statusFlg, PDO::PARAM_STR);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->execute();

    }

    public function delete(int $id)
    {
        $sql = "update troubleReport set delete_flg = '9'
        where id = :id";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }
    
}
