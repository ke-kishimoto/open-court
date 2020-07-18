<?php

namespace entity;

class Participant {
    public $id;
    public $gameId;
    public $occupation;
    public $sex;
    public $name;
    public $email;
    public $companion;
    public $remark;

    public function __construct($gameId, $occupation, $sex, $name, $email, $companion, $remark)
    {
        $this->gameId = $gameId;
        $this->occupation = $occupation;
        $this->sex = $sex;
        $this->name = $name;
        $this->email = $email;
        $this->companion = $companion;
        $this->remark = $remark;
    }
}