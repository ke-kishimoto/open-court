<?php
namespace entity;

class Config {

    public $id;
    public $lineToken;
    public $systemTitle;
    public $bgColor;
    public $logoImgPath;

    public function  __construct($id, $lineToken, $systemTitle, $bgColor, $logoImgPath) {
        $this->id = $id;
        $this->lineToken = $lineToken;
        $this->systemTitle = $systemTitle;
        $this->bgColor = $bgColor;
        $this->logoImgPath = $logoImgPath;
    }
    
}

?>