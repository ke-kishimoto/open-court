<?php

namespace dao;

class ParticipantDao extends BaseDao
{

    public function __construct() 
    {
        parent::__construct();
        $this->tableName = 'participant';
    }

}

?>