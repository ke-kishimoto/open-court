<?php
namespace controller;
use dao\ConfigDao;

class BaseController {

    public function adminHeader() {
        session_start();

        $this->headerCommon();

        //セッションに'user'が無ければログイン画面へ
        if (isset($_SESSION['user']) == null) {
            header('Location: /admin/admin/signIn');
            $_SESSION['user_name'] = '管理者';
        } else {
            $_SESSION['user_name'] = $_SESSION['user']['name'];
        }
    }

    public function userHeader() {
        session_start();

        $this->headerCommon();

        if(!isset($_SESSION['user'])) {
            $_SESSION['loginClass'] = 'hidden';
            $_SESSION['noLoginClass'] = '';
            $_SESSION['user_name'] = 'ゲスト';
            $_SESSION['user_id'] = '';
            $_SESSION['adminMenuFlg'] = '0';
        } else {
            $_SESSION['loginClass'] = '';
            $_SESSION['noLoginClass'] = 'hidden';
            $_SESSION['user_name'] = $_SESSION['user']['name'];
            $_SESSION['user_id'] = $_SESSION['user']['id'];
            if($_SESSION['user']['admin_flg'] == '1') {
                $_SESSION['adminMenuFlg'] = '1';
            } else {
                $_SESSION['adminMenuFlg'] = '0';
            }
        }

    }

    private function headerCommon() {
        $configDao = new ConfigDao();
        $config = $configDao->getConfig(1);
        if ($config['system_title'] !== null) {
            $_SESSION['system_title'] = $config['system_title'];
        } else {
            $_SESSION['system_title'] = 'system_title';
        }
        if ($config['bg_color'] === 'white') {
            $_SESSION['bgColor'] = 'bg-color-white';
        } elseif ($config['bg_color'] == 'orange') {
            $_SESSION['bgColor'] = 'bg-color-orange';
        } else {
            $_SESSION['bgColor'] = 'bg-color-white';
        }
    }
}

