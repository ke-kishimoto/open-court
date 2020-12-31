<?php
namespace dao;

use PDO;

class InquiryDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'inquiry';
    }
    
    public function getInquiryList() 
    {
        $sql = 'select i.*, g.title title 
        from inquiry i
        left join game_info g
        on i.game_id = g.id
        order by id desc';
        $prepare = $this->getPdo()->prepare($sql);
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
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

}

