<?php
// DB接続用
namespace dao;

use PDO;

class MyPDO extends PDO{

    // ローカルMySQL用
    const DSN = 'mysql:dbname=open_court;host=127.0.0.1:8889';
    const USER = 'root';
    const PASSWORD = 'root';
    
    public function __construct()
    {
        // ローカル用
        parent::__construct(
            self::DSN, self::USER, self::PASSWORD
        , 
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    }
}