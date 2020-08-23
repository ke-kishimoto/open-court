<?php
namespace controller;
require_once('../model/entity/Participant.php');
require_once('../model/dao/DetailDao.php');
require_once('../model/dao/ConfigDao.php');
require_once('../model/dao/CompanionDao.php');
require_once('../controller/api/LineApi.php');
require_once('./header.php');
use controller\LineApi;
use entity\Participant;
use dao\DetailDao;
use dao\CompanionDao;
use Exception;

class EventParticipantController {

    // 1人
    public function oneParticipantRegist(Participant $paricipant, array $companions) {
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
    public function multipleParticipantRegist(array $gameIds, Participant $paricipant, array $companions) {
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
