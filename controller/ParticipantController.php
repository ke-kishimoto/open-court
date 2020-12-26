<?php
namespace controller;
use api\LineApi;
use entity\Participant;
use entity\Companion;
use dao\ConfigDao;
use dao\DetailDao;
use dao\UsersDao;
use dao\CompanionDao;
use dao\GameInfoDao;
use dao\DefaultCompanionDao;
use Exception;

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

            // $participant = new Participant(
            //     0
            //     , (int)$_POST['occupation']
            //     , (int)$_POST['sex']
            //     , $_POST['name']
            //     , $_POST['email']
            //     , 0 
            //     , $_POST['remark']
            // );
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
                // $companion[$i-1] = new Companion(0, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
                $companion[$i-1] = new Companion();
                $companion[$i-1]->participationId = 0;
                $companion[$i-1]->occupation = $_POST['occupation-' . $i]; 
                $companion[$i-1]->sex = $_POST['sex-' . $i];
                $companion[$i-1]->name = $_POST['name-' . $i];
            }

            $count = $this->multipleParticipantRegist($_POST['game_id'], $participant, $companion);
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
        $btnLiteral = '登録';
        $pastEvent = false;
        $gameInfoDao = new GameInfoDao();
        // 試合情報取得
        if (isset($_GET['id'])) {
            $gameInfo = $gameInfoDao->getGameInfo($_GET['id']);
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
        include('./view/detail.php');
        include('./view/common/footer.php');
    }

    // 参加処理
    public function participation() {
        parent::userHeader();

        $errMsg = '';
        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            // $participant = new Participant(
            //     (int)$_POST['game_id']
            //     , (int)$_POST['occupation']
            //     , (int)$_POST['sex']
            //     , $_POST['name']
            //     , $_POST['email']
            //     , 0 
            //     , $_POST['remark']
            // );
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
                // $companion[$i-1] = new Companion(0, $_POST['occupation-' . $i], $_POST['sex-' . $i], $_POST['name-' . $i]);
                $companion[$i-1] = new Companion();
                $companion[$i-1]->participationId = 0;
                $companion[$i-1]->occupation = $_POST['occupation-' . $i];
                $companion[$i-1]->sex = $_POST['sex-' . $i];
                $companion[$i-1]->name = $_POST['name-' . $i];
            }

            $errMsg = $this->oneParticipantRegist($participant, $companion);

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
            $detailDao = new DetailDao();
            // LINE通知用に参加者情報とイベント情報を取得
            // $participant = new Participant(
            //     $_POST['game_id']
            //     , 0
            //     , 0
            //     , ''
            //     , $_POST['email']
            //     , 0
            //     , ''
            // );
            $participant = new Participant();
            $participant->gameId = (int)$_POST['game_id'];
            $participant->email = $_POST['email'];
            $id = $detailDao->getParticipantId($participant);
            $msg = '';
            if ($id == null)  {
                $errMsg = '入力されたメールアドレスによる登録がありませんでした。';
            } else {
                if(isset($_POST['password']) && isset($_SESSION['user'])) {
                    $userId = $_SESSION['user']['id'];
                    $usersDao = new UsersDao();
                    $user = $usersDao->getUserById($userId);
                    if(!password_verify($_POST['password'], $user['password'])) {
                        $errMsg = 'パスワードが異なります';
                    }
                }
                if(empty($errMsg)) {
                    $participant = $detailDao->getParticipant($id);
                    $gameInfoDao = new GameInfoDao();
                    $gameInfo = $gameInfoDao->getGameInfo($_POST['game_id']);
                
                    $rowCount = $detailDao->deleteByMailAddress($_POST['game_id'], $_POST['email']);
                
                    $api = new LineApi();
                    $api->cancel_notify($participant, $gameInfo['title'], $gameInfo['game_date']);

                    $configDao = new ConfigDao();
                    $config = $configDao->getConfig(1);
                    // キャンセル待ちの自動繰り上げ
                    if($config['waiting_flg_auto_update'] == 1) {
                        $waitingList = $detailDao->getWitingList($_POST['game_id']);
                        foreach($waitingList as $waitingMember) {
                            if(!$detailDao->limitCheck($_POST['game_id'], 1 + $waitingMember['cnt'])) {
                                $detailDao->updateWaitingFlg($waitingMember['id']);
                            }
                        }
                    }
                }
            }
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

    // 1人
    private function oneParticipantRegist(Participant $paricipant, array $companions) {
        $detailDao = new DetailDao();
        $errMsg = '';
        try {
            // トランザクション開始
            $detailDao->getPdo()->beginTransaction();
            $errMsg = $this->participantRegist($detailDao, $paricipant, $companions);
            $detailDao->getPdo()->commit();
        } catch(Exception $ex) {
            // ロールバック
            $errMsg = 'エラーが発生しました。';
            $detailDao->getPdo()->rollBack();
        }
        // 予約の通知
        if(!$errMsg) {
            $api = new LineApi();
            $api->reserve_notify($paricipant, $_POST['title'], $_POST['date'], $_POST['companion']);
        }
        return $errMsg;
    }
    // 複数人
    private function multipleParticipantRegist(array $gameIds, Participant $paricipant, array $companions) {
        $detailDao = new DetailDao();
        $count = 0;
        try {
            // トランザクション開始
            $detailDao->getPdo()->beginTransaction();
            foreach($gameIds as $gameId) {
                $paricipant->gameId = (int)$gameId;
                $errMsg = $this->participantRegist($detailDao, $paricipant, $companions);
                if(!$errMsg) {
                    $count++;
                }
            }
            $detailDao->getPdo()->commit();
        } catch(Exception $ex) {
            // ロールバック
            $count = 0;
            $errMsg = 'エラーが発生しました。';
            $detailDao->getPdo()->rollBack();
        }
        // 予約の通知
        if($count) {
            $api = new LineApi();
            $api->multiple_reserve($paricipant, $count);
        }
        return $count;
    }

    // 登録処理
    private function participantRegist(DetailDao $detailDao, Participant $paricipant, array $companions) {
        $errMsg = '';
        if($paricipant->email !== '' && $detailDao->existsCheck($paricipant->gameId, $paricipant->email)) {
            $errMsg = '既に登録済みのため登録できません。';
        } else {
            // キャンセル待ちになるかどうかのチェック
            if($detailDao->limitCheck($paricipant->gameId, 1 + count($companions))) {
                $waitingFlg = 1;
            } else {
                $waitingFlg = 0;
            }
            $paricipant->waitingFlg = $waitingFlg;    
            $detailDao->insert($paricipant);
            
            // 同伴者の登録
            if(count($companions) > 0) {
                $id = $detailDao->getParticipantId($paricipant);
                $companionDao = new CompanionDao();
                $companionDao->setPdo($detailDao->getPdo());
                foreach($companions as $companion) {
                    $companion->participantId = (int)$id;
                    $companionDao->insert($companion);
                }
            }
        }
        return $errMsg;
    }


}
