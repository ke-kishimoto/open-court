<?php
namespace dao;

class TroubleReportDao extends BaseDao
{
    public function __construct() 
    {
        parent::__construct();
        $this->tableName = 'trouble_report';
    }

}
