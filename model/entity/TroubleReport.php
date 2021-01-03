<?php

namespace entity;

class TroubleReport extends BaseEntity
{
	public $name;
	public $category;
	public $title;
	public $content;
	public $statusFlg;

	public function __construct()
	{
		
	}
}