<?php

namespace entity;

class GameInfo extends BaseEntity
{
    public $title;
    public $shortTitle;
    public $gameDate;
    public $startTime;
    public $endTime;
    public $place;
    public $limitNumber;
    public $detail;
    public $price1;
    public $price2;
    public $price3;
    public $expenses;
    public $amount;
    public $participantnum;

    public function __construct()
    {
        
    }

}