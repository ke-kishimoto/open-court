<?php
class OpenCourtPDO extends PDO{

    // const DSN = 'pgsql:dbname=open_court host=localhost port=54321';
    // const USER = 'axiz';
    // const PASSWORD = 'axiz';
    const DNS = 'postgres://pxxsmjadcejqnv:8a68cb4f3e311442d688439ea25d780f7e580d0263da70afe65181f20c1a705b@ec2-174-129-255-15.compute-1.amazonaws.com:5432/dd2fk9n2pggjn7';
    
    public function __construct()
    {
        // parent::__construct(OpenCourtPDO::DSN, OpenCourtPDO::USER, OpenCourtPDO::PASSWORD);
        parent::__construct(OpenCourtPDO::DSN);
    }
}