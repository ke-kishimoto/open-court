<?php
namespace api;

use dao\DetailDao;
use dao\GameInfoDao;
use dao\DefaultCompanionDao;
use dao\EventTemplateDao;
use dao\UsersDao;
use dao\NoticeDao;
use service\EventService;

class EventApi {

    public function participantBatchRegist()
    {
        header('Content-type: application/json; charset= UTF-8');

        $data = json_decode(file_get_contents('php://input'), true);
        $participant = [];
        $participant['game_id'] = 0;
        $participant['occupation'] = (int)$data['occupation'];
        $participant['sex'] = (int)$data['sex'];
        $participant['name'] = $data['name'];
        $participant['email'] = $data['email'] ?? '';
        $participant['waiting_flg'] = 0;
        $participant['remark'] = $data['remark'] ?? '';
        $participant['line_id'] = $data['line_id'] ?? '';

        $service = new EventService();
        $count = $service->multipleParticipantRegist($data['idList'], $participant, $data['companion']);

        echo json_encode(['count' => $count]);
    }

    public function participantRegist()
    {
        header('Content-type: application/json; charset= UTF-8');

        $data = json_decode(file_get_contents('php://input'), true);
        $service = new EventService();

        $participant = [];
        $participant['game_id'] = (int)$data['game_id'];
        $participant['occupation'] = (int)$data['occupation'];
        $participant['sex'] = (int)$data['sex'];
        $participant['name'] = $data['name'];
        $participant['email'] = $data['email'] ?? '';
        $participant['remark'] = $data['remark'] ?? '';

        if(($data['id'] ?? '') !== '') {
            $participant['id'] = $data['id'];
            $service->participantUpdate($participant, $data['companion']);
        } else {
            $service->oneParticipantRegist($participant, $data['companion'], EventService::MODE_ADMIN);
        }
        echo json_encode('{}');
    }

    // イベントに対しての参加者リスト取得
    public function getParticipantList()
    {
        header('Content-type: application/json; charset= UTF-8');
        $data = [];
        $gameId = $_POST['game_id'] ?? 0;
        if($gameId !== 0) {
            $dao = new DetailDao();
            $data = $dao->getParticipantList($gameId);
        }
        echo json_encode($data);
    }

    // イベントの参加者の内訳情報取得
    public function getParticipantBreakdown()
    {
        header('Content-type: application/json; charset= UTF-8');

        $gameId = $_POST['game_id'] ?? 0;
        $dao = new DetailDao();
        $data = $dao->getDetail($gameId);
        $gameInfoDao = new GameInfoDao();
        $gameInfo = $gameInfoDao->selectById($gameId);
        $data['limit_number'] = $gameInfo['limit_number'];
        echo json_encode($data);

    }

    // 一月分のイベント一覧の取得
    public function getEventListAtMonth() 
    {

        header('Content-type: application/json; charset= UTF-8');

        $year = $_POST['year'] ?? date('Y');
        $month = $_POST['month'] ?? date('M');
        $gameInfoDao = new GameInfoDao();
        $data = $gameInfoDao->getGameInfoList($year, $month);

        echo json_encode($data);
    }

    // 参加済み・参加予定のイベント一覧の取得
    public function getParticipantEventList() 
    {

        header('Content-type: application/json; charset= UTF-8');
        session_start();

        $eventList = [];
        if(isset($_SESSION['user'])) {
            $detailDao = new DetailDao();
            if(isset($_SESSION['user']['email']) && !empty($_SESSION['user']['email'])) {
                $eventList = $detailDao->getEventListByEmail($_SESSION['user']['email']);
            } elseif(isset($_SESSION['user']['line_id']) && !empty($_SESSION['user']['line_id'])) {
                $eventList = $detailDao->getEventListByLineId($_SESSION['user']['line_id']);
            }
        } 
        echo json_encode($eventList);
    }

    public function getGameInfoListByAfterDate() {

        header('Content-type: application/json; charset= UTF-8');

        $dao = new GameInfoDao();
        $email = $_POST['email'] ?? '';
        $lineId = $_POST['line_id'] ?? '';
        $data = $dao->getGameInfoListByAfterDate(date('Y-m-d'), $email, $lineId);
        echo json_encode($data);

    }
    
    // 参加者削除
    public function deleteParticipant() {
        header('Content-type: application/json; charset= UTF-8');
        $detailDao = new DetailDao();
        // 削除
        $participant = $detailDao->updateDeleteFlg($_POST['participant_id']);

        $info = $detailDao->getDetail($_POST['game_id']);

        echo json_encode($info);
    }

    // 日付クリック時のAjax用の処理
    public function getEventListByDate() {
        header('Content-type: text/plain; charset= UTF-8');
        $gameInfoPDO = new GameInfoDao();
        $gameInfoList = $gameInfoPDO->getGameInfoListByDate($_POST['date']);
        $week = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
        ];
        foreach ($gameInfoList as $gameInfo) {
            // echo 'aaaa';
            // echo $_POST['date'];

            echo '<hr>';
            echo '<a href="EventInfo.php?id=' . $gameInfo['id'] . '">';
            if ($gameInfo['game_date'] < date('Y-m-d')) {
                echo '<span class="event-end">※このイベントは終了しました<br></span>';
            }
            echo $gameInfo['title'] . '<br>';
            // echo '日時：' . $gameInfo['game_date'] . $gameInfo['start_time'] . '～' . $gameInfo['end_time'] . '<br>';
            echo '日時：' . date('n月d日（', strtotime($gameInfo['game_date'])) . $week[date('w', strtotime($gameInfo['game_date']))] . '）';
            echo $gameInfo['start_time'] . '～' . $gameInfo['end_time'] . '<br>';
            echo '場所：' . $gameInfo['place'] . '<br>';
            echo '参加状況：【参加予定：現在' . htmlspecialchars($gameInfo['participants_number']) . '名】定員：' . htmlspecialchars($gameInfo['limit_number']) . '人<br>';
            echo '空き状況：' . htmlspecialchars($gameInfo['mark']);
            echo '</a>';
        }
    }

    public function getDefaultCompanionList() {
        header('Content-type: application/json; charset= UTF-8');
    
        $defaultCompanionDao = new DefaultCompanionDao();
        $companionList = $defaultCompanionDao->getDefaultCompanionList(intval($_POST['id']));
        
        echo json_encode($companionList);
    }

    public function getEventTemplate() {
        header('Content-type: application/json; charset= UTF-8');
    
        $eventTemplateDao = new EventTemplateDao();
        $eventTemplate = $eventTemplateDao->selectById(intval($_POST['id']));
        
        echo json_encode($eventTemplate);
    }

    public function getUserInfo() {
        header('Content-type: application/json; charset= UTF-8');

        $userDao = new UsersDao();
        $user = $userDao->selectById(intval($_POST['user_id']));
        
        echo json_encode($user);
    }

    public function updateAdminFlg() {
        header('Content-type: application/json; charset= UTF-8');
     
        $userDao = new UsersDao();
        // キャンセル待ちフラグの更新
        $userDao->updateAdminFlg($_POST['id']);

        $user = $userDao->selectById($_POST['id']);

        if($user['admin_flg'] == '1') {
            $info['authority_name'] = '管理者';
        } else {
            $info['authority_name'] = '一般';
        }

        echo json_encode($info);
    }

    // public function updateInquiryStatusFlg() {
    //     header('Content-type: application/json; charset= UTF-8');
        
    //     $inquiryDao = new InquiryDao();
    //     // ステータスフラグの更新
    //     $inquiryDao->updateStatusFlg((int)$_POST['id']);
    //     $info = [];
    //     echo json_encode($info);
    // }

    public function updateWaitingFlg() {
        header('Content-type: application/json; charset= UTF-8');
    
        $detailDao = new DetailDao();
        // キャンセル待ちフラグの更新
        $participant = $detailDao->updateWaitingFlg($_POST['id']);

        $info = $detailDao->getDetail($_POST['game_id']);
        $info['waiting_flg'] = $participant['waiting_flg'];

        echo json_encode($info);
    }

    public function getNotice() {
        header('Content-type: application/json; charset= UTF-8');
    
        $noticeDao = new NoticeDao();
        $notice = $noticeDao->selectById(intval($_POST['id']));
        
        echo json_encode($notice);
    }

}
