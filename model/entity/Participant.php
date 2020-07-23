<?php

namespace entity;

class Participant {
    public $id;
    public $gameId;
    public $occupation;
    public $sex;
    public $name;
    public $email;
    public $waitingFlg;
    public $remark;

    public function __construct($gameId, $occupation, $sex, $name, $email, $waitingFlg, $remark)
    {
        $this->gameId = $gameId;
        $this->occupation = $occupation;
        $this->sex = $sex;
        $this->name = $name;
        $this->email = $email;
        $this->waitingFlg = $waitingFlg;
        $this->remark = $remark;
    }
}