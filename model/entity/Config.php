<?php
namespace entity;

class Config {

    public $id;
    public $lineToken;

    public function  __construct($id, $lineToken) {
        $this->id = $id;
        $this->lineToken = $lineToken;
    }
    
}

?>