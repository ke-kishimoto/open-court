<?php
namespace service;

use dao\DetailDao;
use dao\ConfigDao;
use dao\CompanionDao;
use Exception;
use dao\UsersDao;
use dao\GameInfoDao;
use api\LineApi;
use entity\GameInfo;
use entity\Participant;

class EventService
{
    // イベント参加のキャンセル
    public function cancelComplete(Participant $participant, $password, $userId)
    {
        $detailDao = new DetailDao();
        $id = $detailDao->getParticipantId($participant);
        
        $errMsg = '';
        if(!empty($participant->email)) {
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
            }
        }
        if(empty($errMsg)) {
            $participant = $detailDao->getParticipant($id);
            $gameInfoDao = new GameInfoDao();
            $gameInfo = $gameInfoDao->selectById($participant['game_id']);
        
            $rowCount = $detailDao->deleteByMailAddress($participant['game_id'], $participant['email'], $participant['line_id']);
        
            $api = new LineApi();
            // 管理者への通知
            $api->cancel_notify($participant['name'], $gameInfo['title'], $gameInfo['game_date']);
            // 本人への通知
            if(!empty($participant['line_id'])) {
                $msg = $api->createCancelMessage($gameInfo['title'], $gameInfo['game_date']);
                $api->pushMessage($participant['line_id'], $msg);
            }

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

    // １イベントへの参加
    public function oneParticipantRegist(Participant $participant, array $companions, int $notifyFlg = 1) {
        $detailDao = new DetailDao();
        $gameInfoDao = new GameInfoDao();
        $gameInfo = $gameInfoDao->selectById($participant->gameId);
        $errMsg = '';
        try {
            // トランザクション開始
            $detailDao->getPdo()->beginTransaction();
            $errMsg = $this->participantRegist($detailDao, $gameInfoDao, $participant, $companions);
            $detailDao->getPdo()->commit();
        } catch(Exception $ex) {
            // ロールバック
            $errMsg = 'エラーが発生しました。';
            $detailDao->getPdo()->rollBack();
        }
        // 予約の通知
        $api = new LineApi();
        // 管理者への通知
        if(!$errMsg && $notifyFlg === 1) {
            $api->reserve_notify($participant, $gameInfo['title'], $gameInfo['date'], $_POST['companion']);
        }
        // 本人への通知
        if(!empty($participant->line_id)) {
            $msg = $api->createReservationMessage($gameInfo['title'], $gameInfo['date'], $gameInfo['start_time']);
            $api->pushMessage($participant->line_id, $msg);
        }

        return $errMsg;
    }
    // 一活参加登録
    public function multipleParticipantRegist(array $gameIds, Participant $paricipant, array $companions) {
        $detailDao = new DetailDao();
        $gameInfoDao = new GameInfoDao();
        $count = 0;
        try {
            // トランザクション開始
            $detailDao->getPdo()->beginTransaction();
            foreach($gameIds as $gameId) {
                $paricipant->gameId = (int)$gameId;
                $errMsg = $this->participantRegist($detailDao, $gameInfoDao, $paricipant, $companions);
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
        $api = new LineApi();
        // 管理者への通知
        if($count) {
            $api->multiple_reserve($paricipant->name, $count);
        }
        if(!empty($paricipant->lineId)) {
            $msg = "{$count}件のイベントを予約しました。詳細は参加イベント一覧画面をご確認ください。";
            $api->pushMessage($paricipant->lineId, $msg);
        }
        // 本人への通知
        return $count;
    }

    // 登録共通処理
    private function participantRegist(DetailDao $detailDao, GameInfoDao $gameInfoDao, Participant $participant, array $companions) {
        $errMsg = '';
        if($participant->email !== '' && $detailDao->existsCheck($participant->gameId, $participant->email)) {
            $errMsg = '既に登録済みのため登録できません。';
        } else {
            // キャンセル待ちになるかどうかのチェック
            if($detailDao->limitCheck($participant->gameId, 1 + count($companions))) {
                $waitingFlg = 1;
            } else {
                $waitingFlg = 0;
            }
            $participant->waitingFlg = $waitingFlg;
            if ($waitingFlg === 0) {
                $participant->attendance = 1;
            } else {
                // キャンセル待ちの場合はデフォルトで欠席
                $participant->attendance = 2;
            }
            // イベント情報取得
            $gameInfo = $gameInfoDao->selectById($participant->gameId);
            // 参加費の取得
            $participant->amount = $this->getAmount($participant->occupation, $gameInfo);
            // 登録
            $detailDao->insert($participant);
            
            // 同伴者の登録
            if(count($companions) > 0) {
                $id = $detailDao->getParticipantId($participant);
                $companionDao = new CompanionDao();
                $companionDao->setPdo($detailDao->getPdo());
                foreach($companions as $companion) {
                    $companion->participantId = (int)$id;
                    $companion->attendance = $participant->attendance;
                    $companion->amount = $this->getAmount($companion->occupation,$gameInfo);
                    $companionDao->insert($companion);
                }
            }
        }
        return $errMsg;
    }

    // 更新処理
    public function participantUpdate(Participant $participant, array $companions)
    {
        $errMsg = '';
        try {
            $detailDao = new DetailDao();
            $gameInfoDao = new GameInfoDao();
            $companionDao = new CompanionDao();
            // トランザクション開始
            $detailDao->getPdo()->beginTransaction();

            // 同伴者の削除
            $companionDao->deleteByparticipantId($participant->id);
    
            // イベント情報取得
            $gameInfo = $gameInfoDao->selectById($participant->gameId);
            // 参加費取得
            $participant->amount = $this->getAmount($participant->occupation, $gameInfo);
            // 出欠の設定。キャンセル待ちは更新前と同じ
            if ($participant->waitingFlg === 0) {
                $participant->attendance = 1;
            } else {
                // キャンセル待ちの場合はデフォルトで欠席
                $participant->attendance = 2;
            }
            // 更新
            $detailDao->update($participant);
            // 同伴者の登録
            if(count($companions) > 0) {
                $id = $detailDao->getParticipantId($participant);
                $companionDao->setPdo($detailDao->getPdo());
                foreach($companions as $companion) {
                    $companion->participantId = (int)$id;
                    $companion->attendance = $participant->attendance;
                    $companion->amount = $this->getAmount($companion->occupation,$gameInfo);
                    $companionDao->insert($companion);
                }
            }
            $detailDao->getPdo()->commit();
        } catch (Exception $ex) {
            $errMsg ='エラーが発生しました。';
            $detailDao->getPdo()->rollBack();
        }
        return $errMsg;
    }

    // 参加者取得
    private function getAmount(int $occupation, array $gameInfo)
    {
        if ($occupation == 1) {
            return $gameInfo['price1'];
        } elseif ($occupation == 2) {
            return $gameInfo['price2'];
        } elseif ($occupation == 3) {
            return $gameInfo['price3'];
        } else {
            return 0;
        }
    }
}
