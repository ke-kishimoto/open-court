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
            $config = new Config();

            $configDao = new ConfigDao();
            $cfg = $configDao->getConfig(1);
            if(empty($cfg)) {
                $config->id = 1;
                $config->lineToken = '1234567890123456789012345678901234567890';
                $config->systemTitle = 'system_name';
                $config->bgColor = 'white';
                $config->waitingFlgAutoUpdate = 0;
                $configDao->insert($config);
            } else {
                $config->id = $_POST['id'];
                $config->lineToken = $_POST['line_token'];
                $config->systemTitle = $_POST['system_title'];
                $config->bgColor = $_POST['bg_color'];
                $config->waitingFlgAutoUpdate = $_POST['waiting_flg_auto_update'];
                $configDao->update($config);
            }                

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

