<?php
namespace controller;
use dao\ConfigDao;

class BaseController {

    public function adminHeader() {
        session_start();

        //セッションに'user'が無ければログイン画面へ
        if (isset($_SESSION['user']) == null) {
            header('Location: /signIn');
            $_SESSION['user_name'] = '管理者';
        } else {
            $_SESSION['user_name'] = $_SESSION['user']['name'];
        }
    }

}

