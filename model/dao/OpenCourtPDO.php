<?php
class OpenCourtPDO extends PDO{

    // const DATABASE_URL = 'postgres://pxxsmjadcejqnv:8a68cb4f3e311442d688439ea25d780f7e580d0263da70afe65181f20c1a705b@ec2-174-129-255-15.compute-1.amazonaws.com:5432/dd2fk9n2pggjn7';
    const DSN = 'pgsql:dbname=open_court host=localhost port=54321';
    const USER = 'axiz';
    const PASSWORD = 'axiz';
    
    public function __construct()
    {

        // $url = parse_url(getenv('DATABASE_URL'));
        // $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
        parent::__construct(OpenCourtPDO::DSN, OpenCourtPDO::USER, OpenCourtPDO::PASSWORD);
        // parent::__construct($dsn, $url['user'], $url['pass']);
    }
}