<?php
// DB接続用
namespace dao;

use PDO;

class MampPDO extends PDO{

    // ローカルMySQL用
    const DSN = 'mysql:dbname=open_court;host=127.0.0.1:8889';
    const USER = 'root';
    const PASSWORD = 'root';
    
    public function __construct()
    {
        // ローカル用
        parent::__construct(MampPDO::DSN, MampPDO::USER, MampPDO::PASSWORD);

    }
}