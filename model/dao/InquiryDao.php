<?php
namespace dao;

use dao\DaoFactory;

use PDO;
use entity\Inquiry;

class InquiryDao 
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

    public function insert(Inquiry $inquiry) 
    {
        $sql = 'insert into inquiry (game_id, name, email, content, status_flg, register_date)
        values (:game_id, :name, :email, :content, :status_flg, :register_date)';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':game_id', $inquiry->gameId, PDO::PARAM_INT);
        $prepare->bindValue(':name', $inquiry->name, PDO::PARAM_STR);
        $prepare->bindValue(':email', $inquiry->email, PDO::PARAM_STR);
        $prepare->bindValue(':content', $inquiry->content, PDO::PARAM_STR);
        $prepare->bindValue(':status_flg', $inquiry->statusFlg, PDO::PARAM_INT);
        $prepare->bindValue(':register_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->execute();
    }

    public function getInquiryList() 
    {
        $sql = 'select i.*, g.title title 
        from inquiry i
        left join game_info g
        on i.game_id = g.id
        order by id desc';
        $prepare = $this->pdo->prepare($sql);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function updateStatusFlg(int $id) 
    {
        $sql = 'update inquiry set status_flg = 
        case 
            when status_flg = 0 then 1
            when status_flg = 1 then 0
        end
        , update_date = :update_date
        where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

    public function getInquiry(int $id)
    {
        $sql = 'select * from inquiry where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetch();
    }
}

