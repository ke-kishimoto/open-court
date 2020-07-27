<?php

namespace entity;

class Companion {
    public $id;
    public $user_id;
    public $occupation;
    public $sex;
    public $name;

    public function __construct($user_id, $occupation, $sex, $name)
    {
        $this->user_id = $user_id;
        $this->occupation = $occupation;
        $this->sex = $sex;
        $this->name = $name;
    }
}