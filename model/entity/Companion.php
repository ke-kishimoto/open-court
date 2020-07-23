<?php

namespace entity;

class Companion {
    public $id;
    public $participantId;
    public $occupation;
    public $sex;
    public $name;

    public function __construct($participantId, $occupation, $sex, $name)
    {
        $this->participantId = $participantId;
        $this->occupation = $occupation;
        $this->sex = $sex;
        $this->name = $name;
    }
}