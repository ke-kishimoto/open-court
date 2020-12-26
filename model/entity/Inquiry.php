<?php 

namespace entity;

class Inquiry 
{
    public $id;
    public $gameId;
    public $name;
    public $email;
    public $content;
    public $statusFlg;
    public $registerDate;
    public $updateDate;

    public $gameTitle;

    public function __construct()
    {
        
    }

    // public function __construct($gameId, $name, $email, $content, $statusFlg, $registerDate, $updateDate) {
    //     $this->gameId = $gameId;
    //     $this->name = $name;
    //     $this->email = $email;
    //     $this->content = $content;
    //     $this->statusFlg = $statusFlg;
    //     $this->registerDate = $registerDate;
    //     $this->updateDate = $updateDate;
    // }
}