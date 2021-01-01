<?php
namespace entity;

class Config extends BaseEntity
{
    public $lineToken;
    public $systemTitle;
    public $bgColor;
    public $logoImgPath;
    public $waitingFlgAutoUpdate;

    public function __construct()
    {
        
    }
    
}