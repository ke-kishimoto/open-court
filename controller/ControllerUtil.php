<?php
namespace controller;

use dao\DetailDao;
use dao\ConfigDao;
use dao\UsersDao;
use dao\GameInfoDao;
use api\LineApi;
use entity\Participant;

class ControllerUtil
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
}
