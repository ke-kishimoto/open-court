<?php
class Participant {
    public $id;
    public $gameId;
    public $occupation;
    public $sex;
    public $name;

    public function __construct($gameId, $occupation, $sex, $name)
    {
        $this->gameId = $gameId;
        $this->occupation = $occupation;
        $this->sex = $sex;
        $this->name = $name;
    }
}