<?php
namespace controller\admin;
use controller\BaseController;
use entity\GameInfo;
use dao\EventTemplateDao;
use dao\GameInfoDao;
use dao\DetailDao;
use Exception;

class EventController extends BaseController
{

    public function eventTemplate() 
    {
        parent::adminHeader();

        // テンプレ一覧
        $eventTemplateDao = new EventTemplateDao();
        $eventTemplateList = $eventTemplateDao->getEventTemplateList();

        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'テンプレート登録';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/eventTemplate.php');
        include('./view/admin/common/footer.php');
    }

    public function eventTempleteComplete() 
    {
        parent::adminHeader();

        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            $eventTemplateDao = new EventTemplateDao();
            if (isset($_POST['register'])) {
                // 登録・修正''
                // $eventTemplate = new EventTemplate();
                // $eventTemplate->templateName = $_POST['template_name'];
                // $eventTemplate->title = $_POST['title'];
                // $eventTemplate->shortTitle = $_POST['short_title'];
                // $eventTemplate->place = $_POST['place'];
                // $eventTemplate->limitNumber = $_POST['limit_number'];
                // $eventTemplate->detail = $_POST['detail'];
                // $eventTemplate->price1 = (int)$_POST['price1'];
                // $eventTemplate->price2 = (int)$_POST['price2'];
                // $eventTemplate->price3 = (int)$_POST['price3'];

                $eventTemplate = [];
                $eventTemplate['template_name'] = $_POST['template_name'];
                $eventTemplate['title'] = $_POST['title'];
                $eventTemplate['short_title'] = $_POST['short_title'];
                $eventTemplate['place'] = $_POST['place'];
                $eventTemplate['limit_number'] = $_POST['limit_number'];
                $eventTemplate['detail'] = $_POST['detail'];
                $eventTemplate['price1'] = (int)$_POST['price1'];
                $eventTemplate['price2'] = (int)$_POST['price2'];
                $eventTemplate['price3'] = (int)$_POST['price3'];
                                
                if($_POST['id'] == '' || isset($_POST['new'])) {
                    $eventTemplateDao->insert($eventTemplate);
                } else {
                    $eventTemplate['id'] = $_POST['id'];
                    $eventTemplateDao->update($eventTemplate);
                }
            } else {
                if($_POST['id'] != '') {
                    $eventTemplateDao->updateDeleteFlg($_POST['id']);
                }
            }
            unset($_SESSION['csrf_token']);
        } else {
            header('Location: ./index.php');
        }

        $title = 'テンプレート登録完了';
        $msg = 'テンプレートの更新が完了しました。';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/complete.php');
        include('./view/admin/common/footer.php');
    }

    public function eventInfo() 
    {
        parent::adminHeader();

        // テンプレ一覧
        $eventTemplateDao = new EventTemplateDao();
        $eventTemplateList = $eventTemplateDao->getEventTemplateList();

        $gameInfo = null;
        $gameInfoDao = new GameInfoDao();
        $templateAreaClass = 'hidden';
        $participantDisp = '';
        // 試合情報取得
        if (isset($_GET['gameid'])) {
            $gameInfo = $gameInfoDao->selectById($_GET['gameid']);
        }
        if (empty($gameInfo)) {
            // 新規の場合
            //    header('Location: index.php');
            $gameInfo = array(
                'id' => ''
                , 'title' => ''
                , 'short_title' => ''
                , 'game_date' => ''
                , 'start_time' => ''
                , 'end_time' => ''
                , 'place' => ''
                , 'limit_number' => 0
                , 'detail' => ''
                , 'price1' => 0
                , 'price2' => 0
                , 'price3' => 0
            );
            $templateAreaClass = '';
            $participantDisp = 'hidden';

            if(isset($_GET['date'])) {
                $gameInfo['game_date'] = $_GET['date'];
            }
        }
        // 参加者情報取得
        $participantList = null;
        if(!empty($gameInfo['id'])) {
            $detailDao = new DetailDao();
            $participantList = $detailDao->getParticipantList($gameInfo['id']);

            $mailto = 'mailto:?subject=【' . $gameInfo['title'] . '】についてのお知らせ&amp;bcc=';
            foreach($participantList as $participant) {
                if(!empty($participant['email'])) {
                    $mailto .= $participant['email'] . ',';
                }
            }
        }

        $detail = null;
        if(!empty($gameInfo['id'])) {
            $detailDao = new DetailDao();
            $detail = $detailDao->getDetail($gameInfo['id']);
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

        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = 'イベント情報登録';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/eventInfo.php');
        include('./view/admin/common/footer.php');
    }

    public function eventComplete() 
    {
        parent::adminHeader();

        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            $msg = '';
            if (isset($_POST['register'])) {
                // 登録・修正''
                $msg = '登録';
                $gameInfo = new GameInfo();
                $gameInfo->title = $_POST['title'];
                $gameInfo->shortTitle = $_POST['short_title'];
                $gameInfo->gameDate = $_POST['game_date'];
                $gameInfo->startTime = $_POST['start_time'];
                $gameInfo->endTime = $_POST['end_time'];
                $gameInfo->place = $_POST['place'];
                $gameInfo->limitNumber = $_POST['limit_number'];
                $gameInfo->detail = $_POST['detail'];
                $gameInfo->price1 = (int)$_POST['price1'];
                $gameInfo->price2 = (int)$_POST['price2'];
                $gameInfo->price3 = (int)$_POST['price3'];
                
                $gameInfoDao = new GameInfoDao();
                if(empty($_POST['game_id']) || $_POST['game_id'] == '') {
                    $gameInfoDao->insert($gameInfo);
                } else {
                    $gameInfo->id = $_POST['game_id'];
                    $gameInfoDao->update($gameInfo);
                }
            } else {
                $gameInfoDao = new GameInfoDao();
                if($_POST['game_id'] != '') {
                    $msg = '削除';
                    try {
                        $gameInfoDao->getPdo()->beginTransaction();
                        $gameInfoDao->updateDeleteFlg($_POST['game_id']);
                        $gameInfoDao->getPdo()->commit();
                    }catch (Exception $ex) {
                        $gameInfoDao->getPdo()->rollBack();
                    }
                }
            }
            unset($_SESSION['csrf_token']);
        } else {
            header('Location: ./index.php');
        }

        $title = 'イベント登録完了';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/eventComplete.php');
        include('./view/admin/common/footer.php');
    }

}

