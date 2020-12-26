<?php
namespace entity;

class Config 
{

    public $id;
    public $lineToken;
    public $systemTitle;
    public $bgColor;
    public $logoImgPath;
    public $waitingFlgAutoUpdate;

    public function __construct()
    {
        
    }

    // public function  __construct($id, $lineToken, $systemTitle, $bgColor, $logoImgPath, $waitingFlgAutoUpdate) {
    //     $this->id = $id;
    //     $this->lineToken = $lineToken;
    //     $this->systemTitle = $systemTitle;
    //     $this->bgColor = $bgColor;
    //     $this->logoImgPath = $logoImgPath;
    //     $this->waitingFlgAutoUpdate = $waitingFlgAutoUpdate;
    // }
    
}

?>