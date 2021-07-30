<?php
namespace dao;

use dao\BaseDao;

require_once(__DIR__.'/BaseDao.php');

class NoticeDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'notice';
    }
    
}
