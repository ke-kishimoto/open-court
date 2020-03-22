<?php
class GameInfo {

    public $id;
    public $title;
    public $gameDate;
    public $startTime;
    public $endTime;
    public $place;
    public $limitNumber;
    public $detail;

    public function __construct($title, $gameDate, $startTime, $endTime, $place, $limitNumber, $detail)
    {
        $this->title = $title;
        $this->gameDate = $gameDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->place = $place;
        $this->limitNumber = $limitNumber;
        $this->detail = $detail;
    }
}

