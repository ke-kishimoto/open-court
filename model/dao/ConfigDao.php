<?php

namespace dao;

use dao\DaoFactory;
use PDO;
use entity\Config;

class ConfigDao extends BaseDao
{

    private static $systemTitle;
    public static function getSystemTitle() 
    {
        return ConfigDao::$systemTitle;
    }
    public static function setSystemTitle(string $systemTitle) 
    {
        ConfigDao::$systemTitle = $systemTitle;
    }

    public function __construct() 
    {
        parent::__construct();
        $this->tableName = 'config';
    }

}