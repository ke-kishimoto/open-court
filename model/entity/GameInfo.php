<?php
class GameInfo {

    public $id;
    public $title;
    public $gameDate;
    public $startTime;
    public $endTime;
    public $place;
    public $detail;

    public function __construct($title, $gameDate, $startTime, $endTime, $place, $detail)
    {
        $this->title = $title;
        $this->gameDate = $gameDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->place = $place;
        $this->detail = $detail;
    }
}

