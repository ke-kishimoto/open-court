<?php

namespace controller;

class ErrorController extends BaseController
{
    public function index()
    {
        $title = 'エラー';
        include('./view/common/head.php');
        include('./view/error.php');
    }
}