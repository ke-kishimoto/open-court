<?php

namespace entity;

class Companion {
    public $id;
    public $participantId;
    public $occupation;
    public $sex;
    public $name;

    public function __construct()
    {
        
    }

    // public function __construct(int $participantId, int $occupation, int $sex, $name)
    // {
    //     $this->participantId = $participantId;
    //     $this->occupation = $occupation;
    //     $this->sex = $sex;
    //     $this->name = $name;
    // }
}