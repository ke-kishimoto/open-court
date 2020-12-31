<?php
namespace dao;

use PDO;

class EventTemplateDao extends BaseDao
{

    public function __construct() 
    {
        parent::__construct();
        $this->tableName = 'event_template';
    }
   
    public function getEventTemplateList() 
    {
        $sql = 'select * from event_template where delete_flg = 1 order by id';
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    public function delete(int $id)
    {
        // $sql = "delete from event_template where id = :id";
        $sql = "update event_template set delete_flg = 9 where id = :id";
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();

    }
}