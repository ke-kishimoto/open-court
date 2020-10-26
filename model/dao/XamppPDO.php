<?php
// DB接続用
namespace dao;

use PDO;

class XamppPDO extends PDO{

    // ローカルMySQL用
    const DSN = 'mysql:dbname=open_court;host=127.0.0.1';
    const USER = 'root';
    const PASSWORD = '';
    
    public function __construct()
    {
        // ローカル用
        parent::__construct(self::DSN, self::USER, self::PASSWORD);

    }
}