<?php
namespace controller\admin;
use dao\ConfigDao;
use controller\BaseController;


class ConfigController extends BaseController
{

    public function config() {
        parent::adminHeader();
        
        $configDao = new ConfigDao();
        // いずれユーザーIDにする
        $config = $configDao->selectById(1);

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
            $configDao = new ConfigDao();
            $cfg = $configDao->selectById(1);
            // $config = new Config();
            // $config->id = $_POST['id'];
            // $config->lineToken = $_POST['line_token'];
            // $config->lineNotifyFlg = $_POST['line_notify_flg'];
            // $config->sendgridApiKey = $_POST['sendgrid_api_key'] ?? '';
            // $config->systemTitle = $_POST['system_title'];
            // $config->bgColor = $_POST['bg_color'];
            // $config->waitingFlgAutoUpdate = $_POST['waiting_flg_auto_update'];
            // $config->clientId = $_POST['client_id'];
            // $config->clientSecret = $_POST['client_secret'];
            // $config->channelAccessToken = $_POST['channel_access_token'];
            // $config->channelSecret = $_POST['channel_secret'];
            $config = [];
            $config['id'] = $_POST['id'];
            $config['line_token'] = $_POST['line_token'];
            $config['line_notify_flg'] = $_POST['line_notify_flg'];
            $config['sendgrid_api_key'] = $_POST['sendgrid_api_key'] ?? '';
            $config['system_title'] = $_POST['system_title'];
            $config['bg_color'] = $_POST['bg_color'];
            $config['waiting_flg_auto_update'] = $_POST['waiting_flg_auto_update'];
            $config['client_id'] = $_POST['client_id'];
            $config['client_secret'] = $_POST['client_secret'];
            $config['channel_access_token'] = $_POST['channel_access_token'];
            $config['channel_secret'] = $_POST['channel_secret'];
            if(empty($cfg)) {
                $configDao->insert($config);
            } else {
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

