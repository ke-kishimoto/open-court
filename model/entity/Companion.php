<?php

namespace entity;
use entity\BaseEntity;

class Companion extends BaseEntity
{
    public $participantId;
    public $occupation;
    public $sex;
    public $name;
    public $attendance;
    public $amount;
    public $amountRemark;

    public function __construct()
    {
        
    }
}