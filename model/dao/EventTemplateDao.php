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

}