<?php
namespace controller;

class HelpController extends BaseController {
    public function privacyPolicy() {
        parent::userHeader();

        $title = 'プライバシーポリシー';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/privacyPolicy.php');
    }
}
