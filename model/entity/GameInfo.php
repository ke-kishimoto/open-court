<?php

namespace entity;

class GameInfo {

    public $id;
    public $title;
    public $shortTitle;
    public $gameDate;
    public $startTime;
    public $endTime;
    public $place;
    public $limitNumber;
    public $detail;

    public function __construct()
    {
        
    }
    // public function __construct($title, $shortTitle, $gameDate, $startTime, $endTime, $place, $limitNumber, $detail)
    // {
    //     $this->title = $title;
    //     $this->shortTitle = $shortTitle;
    //     $this->gameDate = $gameDate;
    //     $this->startTime = $startTime;
    //     $this->endTime = $endTime;
    //     $this->place = $place;
    //     $this->limitNumber = $limitNumber;
    //     $this->detail = $detail;
    // }
}

