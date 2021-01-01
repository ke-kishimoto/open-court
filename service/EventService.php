<?php
namespace service;

use dao\DetailDao;
use dao\ConfigDao;
use dao\CompanionDao;
use Exception;
use dao\UsersDao;
use dao\GameInfoDao;
use api\LineApi;
use entity\Participant;

class EventService
{
    public function cancelComplete(Participant $participant, $password, $userId)
    {
        $detailDao = new DetailDao();
        $id = $detailDao->getParticipantId($participant);

        if (empty($id))  {
            $errMsg = '入力されたメールアドレスによる登録がありませんでした。';
            return $errMsg;
        } else {
            if(!empty($password) && !empty($userId)) {
                $usersDao = new UsersDao();
                $user = $usersDao->selectById($userId);
                if(!password_verify($password, $user['password'])) {
                    $errMsg = 'パスワードが異なります。';
                    return $errMsg;
                }
            }
            if(empty($errMsg)) {
                $participant = $detailDao->getParticipant($id);
                $gameInfoDao = new GameInfoDao();
                $gameInfo = $gameInfoDao->selectById($participant['game_id']);
            
                $rowCount = $detailDao->deleteByMailAddress($participant['game_id'], $participant['email']);
            
                $api = new LineApi();
                $api->cancel_notify($participant['name'], $gameInfo['title'], $gameInfo['game_date']);

                $configDao = new ConfigDao();
                $config = $configDao->selectById(1);
                // キャンセル待ちの自動繰り上げ
                if($config['waiting_flg_auto_update'] == 1) {
                    $waitingList = $detailDao->getWitingList($participant['game_id']);
                    foreach($waitingList as $waitingMember) {
                        if(!$detailDao->limitCheck($participant['game_id'], 1 + $waitingMember['cnt'])) {
                            $detailDao->updateWaitingFlg($waitingMember['id']);
                        }
                    }
                }
            }
            return '';
        }
    }

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
            $api->multiple_reserve($paricipant->name, $count);
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
