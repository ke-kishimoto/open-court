<?php
// DB接続用
namespace dao;

use PDO;

class TestPDO extends PDO{

    // ローカルMySQL用
    const DSN = 'mysql:dbname=eventman_test;host=127.0.0.1:8889';
    const USER = 'root';
    const PASSWORD = 'root';
    
    public function __construct()
    {
        // ローカル用
        parent::__construct(self::DSN, self::USER, self::PASSWORD);

    }
}