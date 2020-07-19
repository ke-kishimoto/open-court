<?php
// DB接続用
namespace dao;

use PDO;

class HerokuPDO extends PDO{

    // Heroku用
    const DATABASE_URL = '';

    public function __construct()
    {
        // // Heroku用
        $url = parse_url(getenv('DATABASE_URL'));
        $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
        parent::__construct($dsn, $url['user'], $url['pass']);

    }
}