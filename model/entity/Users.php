<?php

namespace entity;

class Users {
    public $id;
    public $admin_flg;
    public $email;
    public $name;
    public $password;
    public $occupation;
    public $sex;
    public $remark;

    public function __construct($admin_flg, $email, $name, $password, $occupation, $sex, $remark)
    {
        $this->admin_flg = $admin_flg;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->occupation = $occupation;
        $this->sex = $sex;
        $this->remark = $remark;
    }
}