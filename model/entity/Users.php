<?php

namespace entity;

class Users {
	public $id;
	public $adminFlg;
	public $email;
	public $name;
	public $password;
	public $occupation;
	public $sex;
	public $remark;

	public function __construct($adminFlg, $email, $name, $password, $occupation, $sex, $remark)
	{
		$this->adminFlg = $adminFlg;
		$this->email = $email;
		$this->name = $name;
		$this->password = $password;
		$this->occupation = $occupation;
		$this->sex = $sex;
		$this->remark = $remark;
	}
}