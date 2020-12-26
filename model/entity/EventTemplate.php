<?php

namespace entity;

class EventTemplate {

    public $id;
    public $templateName;
    public $title;
    public $shortTitle;
    public $place;
    public $limitNumber;
    public $detail;

    public function __construct()
    {
        
    }
    // public function __construct($templateName, $title, $shortTitle, $place, $limitNumber, $detail)
    // {
    //     $this->templateName = $templateName;
    //     $this->title = $title;
    //     $this->shortTitle = $shortTitle;
    //     $this->place = $place;
    //     $this->limitNumber = $limitNumber;
    //     $this->detail = $detail;
    // }
}

?>