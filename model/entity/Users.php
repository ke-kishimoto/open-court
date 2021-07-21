<?php

namespace entity;

class Users extends BaseEntity
{
	public $adminFlg;
	public $email;
	public $name;
	public $password;
	public $occupation;
	public $sex;
	public $remark;
	public $tel;
	public $lineId;
	public $accessToken;
	public $refreshToken;

	public function __construct()
	{
		
	}
}