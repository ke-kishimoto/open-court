<?php

namespace entity;

class Participant extends BaseEntity
{
    public $gameId;
    public $occupation;
    public $sex;
    public $name;
    public $email;
    public $waitingFlg;
    public $remark;
    public $attendance;
    public $amount;
    public $tel;

    public function __construct()
    {
        
    }

}