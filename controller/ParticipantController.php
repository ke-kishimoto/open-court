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
        parent::adminHeader();

        $gameId = $_GET['gameid'];
        $title = '参加者名一覧';
        include('./view/common/head.php');
        include('./view/participantNameList.php');
    }

    // キャンセル処理
    public function cancelComplete() {

        $errMsg = '';
        if(isset($_POST)) {

            $service = new EventService();
            $participant = [];
            $participant['game_id'] = (int)$_POST['game_id'];
            $participant['email'] = $_POST['email'] ?? '';
            $participant['line_id'] = $_POST['line_id'] ?? '';
            if(isset($_POST['password']) && isset($_SESSION['user'])) {
                $password = $_POST['password'];
                $userId = $_SESSION['user']['id'];
            } else {
                $password = '';
                $userId = '';
            }
            $errMsg = $service->cancelComplete($participant, $password, $userId, EventService::MODE_USER);
        }
        
        if(empty($errMsg)) {
            $title = 'キャンセル完了';
            $msg = '予約のキャンセルが完了しました';
            include('./view/common/head.php');
            include('./view/complete.php');
        } else {
            if(isset($_SESSION['user'])) {
                $email = $_SESSION['user']['email'];
                $mode = 'login';
            } else {
                $email = '';
                $mode = 'guest';
            }
            $gameId = $_POST['game_id'];
            $title = 'キャンセル';
            include('./view/common/head.php');
            include('./view/cancelForm.php');
        }
    }

}
