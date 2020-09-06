<?php
namespace controller;

class HelpController extends BaseController {
    public function privacyPolicy() {
        parent::adminHeader();

        $title = 'プライバシーポリシー';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/privacyPolicy.php');
        include('./view/common/footer.php');
    }
}
