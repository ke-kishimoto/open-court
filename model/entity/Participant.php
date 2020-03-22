<?php
class Participant {
    public $id;
    public $gameId;
    public $occupation;
    public $sex;
    public $name;
    public $companion;
    public $remark;

    public function __construct($gameId, $occupation, $sex, $name, $companion, $remark)
    {
        $this->gameId = $gameId;
        $this->occupation = $occupation;
        $this->sex = $sex;
        $this->name = $name;
        $this->companion = $companion;
        $this->remark = $remark;
    }
}