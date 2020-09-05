<?php
// DB接続用
namespace dao;

use PDO;

class HerokuPDO extends PDO{

    // Heroku用
    const DATABASE_URL = 'postgres://pxxsmjadcejqnv:8a68cb4f3e311442d688439ea25d780f7e580d0263da70afe65181f20c1a705b@ec2-174-129-255-15.compute-1.amazonaws.com:5432/dd2fk9n2pggjn7';

    public function __construct()
    {
        // // Heroku用
        $url = parse_url(getenv('DATABASE_URL'));
        $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
        parent::__construct($dsn, $url['user'], $url['pass']);

    }
}