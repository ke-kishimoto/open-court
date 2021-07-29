<?php 

namespace entity;

use entity\BaseEntity;

class Inquiry extends BaseEntity
{
    public $gameId;
    public $gameTitle;
    public $name;
    public $email;
    public $lineId;
    public $content;
    public $statusFlg;

    public function __construct()
    {
        
    }

}