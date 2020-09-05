<?php
namespace controller\admin;
require_once('./model/entity/GameInfo.php');
require_once('./model/entity/EventTemplate.php');
require_once('./model/dao/GameInfoDao.php');
require_once('./model/dao/DetailDao.php');
require_once('./model/dao/EventTemplateDao.php');
use controller\BaseController;
use entity\GameInfo;
use entity\EventTemplate;
use dao\EventTemplateDao;
use dao\GameInfoDao;
use dao\DetailDao;
use Exception;

class EventController extends BaseController
{

    public function eventTemplate() {
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

    public function eventTempleteComplete() {
        parent::adminHeader();

        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            $msg = '';
            if (isset($_POST['register'])) {
                // 登録・修正''
                $msg = '登録';
                $eventTemplate = new EventTemplate(
                    $_POST['template_name']
                    , $_POST['title']
                    , $_POST['short_title']
                    , $_POST['place']
                    , $_POST['limit_number']
                    , $_POST['detail']
                );
                
                $eventTemplateDao = new EventTemplateDao();
                
                if($_POST['id'] == '' || isset($_POST['new'])) {
                    $eventTemplateDao->insert($eventTemplate);
                } else {
                    $eventTemplate->id = $_POST['id'];
                    $eventTemplateDao->update($eventTemplate);
                }
            } else {
                $eventTemplateDao = new EventTemplateDao();
                if($_POST['id'] != '') {
                    $msg = '削除';
                    $eventTemplateDao->delete($_POST['id']);
                }
            }
            unset($_SESSION['csrf_token']);
        } else {
            header('Location: ./index.php');
        }

        $title = 'テンプレート登録完了';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        $msg = 'テンプレートの更新が完了しました。';
        include('./view/admin/complete.php');
        include('./view/admin/common/footer.php');
    }

    public function eventInfo() {
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
            $gameInfo = $gameInfoDao->getGameInfo($_GET['gameid']);
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

    public function eventComplete() {
        parent::adminHeader();

        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            $msg = '';
            if (isset($_POST['register'])) {
                // 登録・修正''
                $msg = '登録';
                $gameInfo = new GameInfo(
                    $_POST['title']
                    , $_POST['short_title']
                    , $_POST['game_date']
                    , $_POST['start_time']
                    , $_POST['end_time']
                    , $_POST['place']
                    , $_POST['limit_number']
                    , $_POST['detail']
                );
                
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
                        $gameInfoDao->delete($_POST['game_id']);
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

