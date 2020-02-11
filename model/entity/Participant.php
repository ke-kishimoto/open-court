<?php
class Participant {
    public $id;
    public $gameId;
    public $occupation;
    public $sex;
    public $name;
    public $remark;

    public function __construct($gameId, $occupation, $sex, $name, $remark)
    {
        $this->gameId = $gameId;
        $this->occupation = $occupation;
        $this->sex = $sex;
        $this->name = $name;
        $this->remark = $remark;
    }
}