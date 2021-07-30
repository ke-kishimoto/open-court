<?php
namespace service;

use dao\DetailDao;
use dao\ConfigDao;
use dao\CompanionDao;
use Exception;
use dao\UsersDao;
use dao\GameInfoDao;
use api\LineApi;

class EventService
{
    const MODE_USER = 0;
    const MODE_ADMIN = 1;
    const MODE_LINE = 2;

    // イベント参加のキャンセル
    public function cancelComplete($participant, $password, $userId, int $mode = self::MODE_USER)
    {
        $detailDao = new DetailDao();
        $id = $detailDao->getParticipantId($participant);
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
        
        $errMsg = '';
        if(isset($participant['email']) && !empty($participant['email'])) {
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
        
            // メールアドレスかLINE IDで削除する
            $detailDao->deleteByMailAddress($participant['game_id'], $participant['email'] ?? '', $participant['line_id'] ?? '');
        
            $api = new LineApi();
            // 管理者への通知
            if(!$errMsg && $config['line_notify_flg'] == '1' && $mode !== self::MODE_ADMIN) {
                $api->cancel_notify($participant['name'], $gameInfo['title'], $gameInfo['game_date']);
            }
            // 本人への通知
            if(!empty($participant['line_id']) && $mode === self::MODE_USER) {
                $msg = $api->createCancelMessage($gameInfo['title'], $gameInfo['game_date']);
                $api->pushMessage($participant['line_id'], $msg);
            }

            $configDao = new ConfigDao();
            $config = $configDao->selectById(1);
            // キャンセル待ちの自動繰り上げ
            if($config['waiting_flg_auto_update'] == 1) {
                // 通知用のメッセージ
                $msg = "以下のイベントでキャンセル者が出たため、キャンセル待ちが解除されました。ご確認お願いします。\n";
                $msg .= "タイトル：{$gameInfo['title']}\n";
                $msg .= "日付：{$gameInfo['game_date']}\n";
                $msg .= "開始時刻：{$gameInfo['start_time']}\n";
                $waitingList = $detailDao->getWitingList($participant['game_id']);
                foreach($waitingList as $waitingMember) {
                    if(!$detailDao->limitCheck($participant['game_id'], 1 + $waitingMember['cnt'])) {
                        $detailDao->updateWaitingFlg($waitingMember['id']);
                        // LINEでログインしたユーザーの場合、くり上がりを通知する
                        if(!empty($waitingMember['line_id'])) {
                            $api->pushMessage($waitingMember['line_id'], $msg);
                        }
                    }
                }
            }
        }
        return '';
    }

    // １イベントへの参加
    public function oneParticipantRegist($participant, array $companions, int $mode = self::MODE_USER) {
        $detailDao = new DetailDao();
        $gameInfoDao = new GameInfoDao();
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
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
        $gameInfo = $gameInfoDao->selectById($participant['game_id']);
        // 予約の通知
        $api = new LineApi();
        // 管理者への通知
        if(!$errMsg && $config['line_notify_flg'] == '1' && $mode !== self::MODE_ADMIN) {
            $api->reserve_notify($participant, $gameInfo['title'], $gameInfo['game_date'], $_POST['companion'] ?? '0');
        }
        // 本人への通知
        if(!empty($participant['line_id']) && $mode === self::MODE_USER) {
            $msg = $api->createReservationMessage($gameInfo['title'], $gameInfo['game_date'], $gameInfo['start_time']);
            $api->pushMessage($participant['line_id'], $msg);
        }

        return $errMsg;
    }
    // 一活参加登録
    public function multipleParticipantRegist(array $gameIds, $paricipant, array $companions) {
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
        // 本人への通知
        if(!empty($paricipant->lineId)) {
            $msg = "{$count}件のイベントを予約しました。詳細は参加イベント一覧画面をご確認ください。";
            $api->pushMessage($paricipant->lineId, $msg);
        }
        return $count;
    }

    // 登録共通処理
    private function participantRegist(DetailDao $detailDao, GameInfoDao $gameInfoDao, $participant, array $companions) {
        $errMsg = '';
        if(!empty($participant['email']) && $detailDao->existsCheck($participant['game_id'], $participant['email'])) {
            $errMsg = '既に登録済みのため登録できません。';
        } else {
            // キャンセル待ちになるかどうかのチェック
            if($detailDao->limitCheck($participant['game_id'], 1 + count($companions))) {
                $waitingFlg = 1;
            } else {
                $waitingFlg = 0;
            }
            $participant['waiting_flg'] = $waitingFlg;
            if ($waitingFlg === 0) {
                $participant['attendance'] = 1;
            } else {
                // キャンセル待ちの場合はデフォルトで欠席
                $participant['attendance'] = 2;
            }
            // イベント情報取得
            $gameInfo = $gameInfoDao->selectById($participant['game_id']);
            // 参加費の取得
            if(!empty($participant['occupation'])) {
                $participant['amount'] = $this->getAmount($participant['occupation'], $gameInfo);
            }
            // 登録
            $detailDao->insert($participant);
            
            // 同伴者の登録
            if(count($companions) > 0) {
                $id = $detailDao->getParticipantId($participant);
                $companionDao = new CompanionDao();
                $companionDao->setPdo($detailDao->getPdo());
                foreach($companions as $companion) {
                    $companion['participant_id'] = (int)$id;
                    $companion['attendance'] = $participant['attendance'];
                    $companion['amount'] = $this->getAmount($companion['occupation'],$gameInfo);
                    $companionDao->insert($companion);
                }
            }
        }
        return $errMsg;
    }

    // 更新処理
    public function participantUpdate($participant, array $companions)
    {
        $errMsg = '';
        try {
            $detailDao = new DetailDao();
            $gameInfoDao = new GameInfoDao();
            $companionDao = new CompanionDao();
            // トランザクション開始
            $detailDao->getPdo()->beginTransaction();

            // 同伴者の削除
            $companionDao->deleteByparticipantId($participant['id']);
    
            // イベント情報取得
            $gameInfo = $gameInfoDao->selectById($participant['game_id']);
            // 参加費取得
            $participant['amount'] = $this->getAmount($participant['occupation'], $gameInfo);
            // 出欠の設定。キャンセル待ちは更新前と同じ
            if ($participant['waiting_flg'] === 0) {
                $participant['attendance'] = 1;
            } else {
                // キャンセル待ちの場合はデフォルトで欠席
                $participant['attendance'] = 2;
            }
            // 更新
            $detailDao->update($participant);
            // 同伴者の登録
            if(count($companions) > 0) {
                $id = $detailDao->getParticipantId($participant);
                $companionDao->setPdo($detailDao->getPdo());
                foreach($companions as $companion) {
                    $companion['participant_id'] = (int)$id;
                    $companion['attendance'] = $participant['attendance'];
                    $companion['amount'] = $this->getAmount($companion['occupation'],$gameInfo);
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
