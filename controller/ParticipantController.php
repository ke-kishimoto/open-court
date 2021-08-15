<?php
namespace controller;
use service\EventService;

class ParticipantController extends BaseController
{

    // 参加一括登録画面
    public function eventBatchRegist() {

        // CSFR対策
        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'イベント詳細';
        include('./view/common/head.php');
        include('./view/eventBatchRegist.php');
    }

    // イベント詳細画面
    public function eventInfo() {

        $title = 'イベント詳細';
        include('./view/common/head.php');
        include('./view/detail.php');
    }

    
    public function cancel() {
        
        if(isset($_SESSION['user'])) {
            $email = $_SESSION['user']['email'];
            $mode = 'login';
        } else {
            $email = '';
            $mode = 'guest';
        }
        $gameId = $_GET['gameid'];

        $title = 'キャンセル';
        include('./view/common/head.php');
        include('./view/cancelForm.php');
    }

    public function participantNameList() {

        $title = '参加者名一覧';
        include('./view/common/head.php');
        include('./view/participantNameList.php');
    }

}
