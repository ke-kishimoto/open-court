<?php

namespace entity;

class EventTemplate extends BaseEntity
{
    public $templateName;
    public $title;
    public $shortTitle;
    public $place;
    public $limitNumber;
    public $detail;
    public $price1;
    public $price2;
    public $price3;

    public function __construct()
    {
        
    }
}