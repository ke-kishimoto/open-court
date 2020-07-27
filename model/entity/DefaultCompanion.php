<?php

namespace entity;

class DefaultCompanion {
    public $id;
    public $userId;
    public $occupation;
    public $sex;
    public $name;

    public function __construct($userId, $occupation, $sex, $name)
    {
        $this->userId = $userId;
        $this->occupation = $occupation;
        $this->sex = $sex;
        $this->name = $name;
    }
}