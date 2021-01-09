<?php
namespace controller;
use entity\Participant;
use entity\Companion;
use dao\DetailDao;
use dao\GameInfoDao;
use dao\CompanionDao;
use dao\DefaultCompanionDao;
use service\EventService;

class ParticipantController extends BaseController
{

    // 参加一括登録処理
    public function eventBatchRegist() {
        parent::userHeader();

        $gameInfoDao = new GameInfoDao();
        date_default_timezone_set('Asia/Tokyo');
        $gameInfoList = $gameInfoDao->getGameInfoListByAfterDate(date('Y-m-d'), $_SESSION['user']['email']);

        // CSFR対策
        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        if (isset($_SESSION['user'])) {
            $occupation = $_SESSION['user']['occupation'];
            $sex = $_SESSION['user']['sex'];
            $defaultCompanionDao = new DefaultCompanionDao();
            $companions = $defaultCompanionDao->getDefaultCompanionList($_SESSION['user']['id']);

        } else {
            $occupation = null;
            $sex = null;
            $companions = [];
        }

        $title = 'イベント詳細';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/eventBatchRegist.php');
        include('./view/common/footer.php');
    }

    // 一括登録
    public function eventBatchRegistComplete() {
        parent::userHeader();

        $errMsg = '';
        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            $participant = new Participant();
            $participant->gameId = 0;
            $participant->occupation = (int)$_POST['occupation'];
            $participant->sex = (int)$_POST['sex'];
            $participant->name = $_POST['name'];
            $participant->email = $_POST['email'];
            $participant->waitingFlg = 0;
            $participant->remark = $_POST['remark'];
            
            $companion = [];
            for($i = 1; $i <= $_POST['companion']; $i++) {
                $companion[$i-1] = new Companion();
                $companion[$i-1]->participationId = 0;
                $companion[$i-1]->occupation = $_POST['occupation-' . $i]; 
                $companion[$i-1]->sex = $_POST['sex-' . $i];
                $companion[$i-1]->name = $_POST['name-' . $i];
            }

            $service = new EventService();
            $count = $service->multipleParticipantRegist($_POST['game_id'], $participant, $companion);
            if($count) {
                $msg = "{$count}件のイベントに登録しました。";
            } else {
                $msg = '登録されたイベントはありませんでした。';
            }

            unset($_SESSION['csrf_token']);
            $title = 'イベント参加登録完了';
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/complete.php');
            include('./view/common/footer.php');

        } else {
            header('Location: /index.php');
        }
    }

    // イベント詳細画面
    public function eventInfo() {
        parent::userHeader();

        $gameInfo = null;
        $limitFlg = false;
        $pastEvent = false;
        $gameInfoDao = new GameInfoDao();
        // 試合情報取得
        if (isset($_GET['id'])) {
            $gameInfo = $gameInfoDao->selectById($_GET['id']);
            $detailDao = new DetailDao();
            $limitFlg = $detailDao->limitCheck($gameInfo['id'], 0);
            $detail = $detailDao->getDetail($gameInfo['id']);
            $participantList = $detailDao->getParticipantList($gameInfo['id']);
            if($limitFlg) {
                $btnClass = 'btn btn-warning';
                $btnLiteral = 'キャンセル待ちとして登録';
            }
            // イベント日が過去の場合は登録フォームを隠す
            date_default_timezone_set('Asia/Tokyo');
            if ($gameInfo['game_date'] < date('Y-m-d')) {
                $pastEvent = true;
            }
        }

        if (empty($gameInfo)) {
            header('Location: index.php');
        }

        if(empty($detail)) {
            $detail = array('cnt' => 0
                , 'limit_number' => 0
                , 'sya_women' => 0
                , 'sya_men' => 0
                , 'dai_women' => 0
                , 'dai_men' => 0
                , 'kou_women' => 0
                , 'kou_men' => 0
                , 'waiting_cnt' => 0
            );
        }

        // CSFR対策
        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        if (isset($_SESSION['user'])) {
            $prepare = $detailDao->query(
                "select * from participant 
                where game_id = :game_id
                and email = :email
                and delete_flg = 1
                "
            , [
                'game_id' => $gameInfo['id']
                , 'email' =>$_SESSION['user']['email']
            ]);
            $participant = $prepare->fetch();
            if(!empty($participant)) {
                $participantId = $participant['id'];
                $occupation = $participant['occupation'];
                $sex = $participant['sex'];
                $companionDao = new CompanionDao();
                $companions = $companionDao->getCompanionList((int)$participant['id']);
                $Registered = true;
            } else {
                $participantId = '';
                $occupation = $_SESSION['user']['occupation'];
                $sex = $_SESSION['user']['sex'];
                $defaultCompanionDao = new DefaultCompanionDao();
                $companions = $defaultCompanionDao->getDefaultCompanionList((int)$_SESSION['user']['id']);
                $Registered = false;
            }

        } else {
            $participantId = '';
            $occupation = null;
            $sex = null;
            $companions = [];
            $Registered = true;
        }

        $title = 'イベント詳細';
        include('./view/common/head.php');
        include('./view/common/header.php');
        include('./view/detail.php');
        include('./view/common/footer.php');
    }

    // 参加処理
    public function participation() {
        parent::userHeader();

        $errMsg = '';
        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            $participant = new Participant();
            $participant->gameId = (int)$_POST['game_id'];
            $participant->occupation = (int)$_POST['occupation'];
            $participant->sex = (int)$_POST['sex'];
            $participant->name = $_POST['name'];
            $participant->email = $_POST['email'];
            $participant->waitingFlg = 0;
            $participant->remark = $_POST['remark'];
        
            $companion = [];
            for($i = 1; $i <= $_POST['companion']; $i++) {
                $companion[$i-1] = new Companion();
                $companion[$i-1]->participantId = 0;
                $companion[$i-1]->occupation = $_POST['occupation-' . $i];
                $companion[$i-1]->sex = $_POST['sex-' . $i];
                $companion[$i-1]->name = $_POST['name-' . $i];
            }

            $service = new EventService();
            if(isset($_POST['insert'])) {
                $errMsg = $service->oneParticipantRegist($participant, $companion);
            } elseif(isset($_POST['update'])) {
                $participant->id = $_POST['participantId'];
                $errMsg = $service->participantUpdate($participant, $companion);
            }

            unset($_SESSION['csrf_token']);
            if(empty($errMsg)) {
                $title = 'イベント参加登録完了';
                $msg = 'イベント参加登録が完了しました。';
                include('./view/common/head.php');
                include('./view/common/header.php');
                include('./view/complete.php');
                include('./view/common/footer.php');
            } else {
                $title = 'イベント参加登録完了';
                $msg = '入力されたメールアドレスで既に登録済みです。';
                include('./view/common/head.php');
                include('./view/common/header.php');
                include('./view/complete.php');
                include('./view/common/footer.php');
            }
        } else {
            header('Location: /index.php');
        }
    }

    public function cancel() {
        parent::userHeader();
        
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
        include('./view/common/header.php');
        include('./view/cancelForm.php');
        include('./view/common/footer.php');
    }

    // キャンセル処理
    public function cancelComplete() {
        parent::userHeader();

        $errMsg = '';
        if(isset($_POST)) {

            $service = new EventService();
            $participant = new Participant();
            $participant->gameId = (int)$_POST['game_id'];
            $participant->email = $_POST['email'];
            if(isset($_POST['password']) && isset($_SESSION['user'])) {
                $password = $_POST['password'];
                $userId = $_SESSION['user']['id'];
            } else {
                $password = '';
                $userId = '';
            }
            $errMsg = $service->cancelComplete($participant, $password, $userId);
        }
        
        if(empty($errMsg)) {
            $title = 'キャンセル完了';
            $msg = '予約のキャンセルが完了しました';
            include('./view/common/head.php');
            include('./view/common/header.php');
            include('./view/complete.php');
            include('./view/common/footer.php');
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
            include('./view/common/header.php');
            include('./view/cancelForm.php');
            include('./view/common/footer.php');
        }
    }

}
