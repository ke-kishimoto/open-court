<?php
namespace entity;

class Config {

    public $id;
    public $lineToken;
    public $systemTitle;

    public function  __construct($id, $lineToken, $systemTitle) {
        $this->id = $id;
        $this->lineToken = $lineToken;
        $this->systemTitle = $systemTitle;
    }
    
}

?>