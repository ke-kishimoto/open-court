<?php
namespace dao;

class ApiLogDao extends BaseDao
{
    public function __construct() 
    {
        parent::__construct();
        $this->tableName = 'api_log';
    }
}