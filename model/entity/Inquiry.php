<?php 

namespace entity;

use entity\BaseEntity;

class Inquiry extends BaseEntity
{
    public $gameId;
    public $name;
    public $email;
    public $content;
    public $statusFlg;

    public function __construct()
    {
        
    }

}