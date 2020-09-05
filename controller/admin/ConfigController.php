<?php
namespace controller\admin;
use dao\ConfigDao;
use entity\Config;
use controller\BaseController;


class ConfigController extends BaseController
{

    public function config() {
        parent::adminHeader();
        
        $configDao = new ConfigDao();
        // いずれユーザーIDにする
        $config = $configDao->getConfig(1);

        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'システム設定';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/config.php');
        include('./view/admin/common/footer.php');
    }

    public function configComplete() {
        parent::adminHeader();

        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {
            // 登録・修正
            $config = new Config(
                $_POST['id']
                , $_POST['line_token']
                , $_POST['system_title']
                , $_POST['bg_color']
                , ''
            );
                
            $configDao = new ConfigDao();
            $configDao->update($config);

            unset($_SESSION['csrf_token']);

            // header('Location: ./');
        } else {
            header('Location: /index.php');
        }

        $title = 'システム設定完了';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        $msg = 'システム設定が完了しました。';
        include('./view/admin/complete.php');
        include('./view/admin/common/footer.php');
    }
}

