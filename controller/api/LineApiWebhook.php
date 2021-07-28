<?php
namespace api;

use api\LineApi;
use dao\GameInfoDao;
use dao\DetailDao;
use dao\UsersDao;
use entity\Users;
use entity\Participant;
use service\EventService;

class LineApiWebhook
{
    const QUICK_REPLY_NUM = 13;  // クイックリプライできるのが最大13件らしい

    // メッセージを受信した時
    public function receiveMessageText($event, $channelAccessToken)
    {
        $text = $event['message']['text'];
        $url = 'https://api.line.me/v2/bot/message/reply'; // リプライ
        $ch = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer {$channelAccessToken}"
        );
        // dataの取得
        if($text === '予約' || $text === 'キャンセル') {
            $data = $this->eventSelect($event, $text);
        } elseif($text === '予約確認') {
            $data = $this->bookingConfirmation($event);
        } elseif($text === '職種') {
            $data = $this->occupationSelect($event);
        } elseif($text === '性別') {
            $data = $this->genderSelect($event);
        } else {
            $data = $this->atherMessage($event);
        }
        // リクエストの送信
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        curl_exec($ch);
        curl_close($ch);
    }

    // postbackを受信した時
    public function receivePostback($event, $channelAccessToken)
    {
        $data = explode('&', $event['postback']['data']);
        // 文字列から連想配列を作成
        foreach($data as $item) {
            $keyValue = explode('=', $item);
            $data[$keyValue[0]] = $keyValue[1];    
        }
        if(isset($data['action']) && $data['action'] === 'profile') {
            $this->profileRegist($event, $channelAccessToken);
        } elseif(isset($data['action']) && $data['action'] === 'select') {
            $this->eventDetail($event, $channelAccessToken, $data);
        } elseif(isset($data['action']) && ($data['action'] === 'reserve' || $data['action'] === 'cancel')) {
            $this->eventReserveOrCancel($event, $channelAccessToken, $data);
        }
    }

    // イベント選択のクイックリプライを返す
    private function eventSelect($event, $text)
    {
        if($text === '予約') {
            $gameInfoDao = new GameInfoDao();
            $gameInfoList = $gameInfoDao->getGameInfoListByAfterDate(date('Y-m-d'), '', $event['source']['userId']);
            $msg = '予約したいイベントを選択してください。';
            $mode = 'reserve';
        } else {
            $detailDao = new DetailDao();
            $gameInfoList = $detailDao->getEventListByLineId($event['source']['userId'], date('Y-m-d'));
            $msg = 'キャンセルしたいイベントを選択してください。';
            $mode = 'cancel';
        }
        $items = [];
        foreach($gameInfoList as $gameInfo) {
            $items[] = [
                'type' => 'action', 
                'action' => [
                    'type' => 'postback',
                    'label' => "{$gameInfo['game_date']} {$gameInfo['short_title']}",
                    'data' => "action=select&mode={$mode}&id={$gameInfo['id']}",
                    'displayText' => "{$gameInfo['game_date']} {$gameInfo['short_title']}"
                ]
            ];
            
            if(count($items) >= self::QUICK_REPLY_NUM) {
                break;
            }
        }
        // 応答メッセージを返す 
        return json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $msg,                            
                    'quickReply' => [
                        'items' =>  $items
                    ]
                ]
            ]
        ]);
    }

    private function bookingConfirmation($event)
    {
        $detailDao = new DetailDao();
        $gameInfoList = $detailDao->getEventListByLineId($event['source']['userId'], date('Y-m-d'));
        if(count($gameInfoList) === 0) {
            $msg = '予約済みのイベントはありません。';
        } else {
            $msg = "予約済みイベント一覧\n";
            foreach($gameInfoList as $gameInfo) {
                $msg .= "----------------------------------------\n";
                $msg .= "タイトル：{$gameInfo['title']}\n";
                $msg .= "日付：{$gameInfo['game_date']}\n";
                $msg .= "開始時刻{$gameInfo['start_time']}\n";
                if($gameInfo['waiting_flg'] == '1') {
                    $msg .= "※キャンセル待ち\n";
                }
            }
            $msg .= "----------------------------------------\n";
            $msg .= "合計" . count($gameInfoList) . "件\n";
        }
        return json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $msg,
                ]
            ]
        ]);
    }

    private function occupationSelect($event) 
    {
        return json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' =>  '職種を選択してください。（高校生以下の場合は高校生を選択してください）',                            
                    'quickReply' => [
                        'items' => [
                            [
                            'type' => 'action',
                            'action' => [
                                'type' => 'postback',
                                'label' => '社会人',
                                'data' => "action=profile&type=occupation&id=1",
                                'displayText' => '社会人'
                                ]
                            ],
                            [
                            'type' => 'action',
                            'action' => [
                                'type' => 'postback',
                                'label' => '学生（大学・専門学校）',
                                'data' => "action=profile&type=occupation&id=2",
                                'displayText' => '学生（大学・専門学校）'
                                ]
                            ],
                            [
                            'type' => 'action',
                            'action' => [
                                'type' => 'postback',
                                'label' => '高校生',
                                'data' => "action=profile&type=occupation&id=3",
                                'displayText' => '高校生'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    private function genderSelect($event)
    {
        return json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' =>  '性別を選択してください。',                            
                    'quickReply' => [
                        'items' =>  [
                            [
                            'type' => 'action',
                            'action' => [
                                'type' => 'postback',
                                'label' => '男性',
                                'data' => "action=profile&type=sex&id=1",
                                'displayText' => '男性'
                                ]
                            ],
                            [
                            'type' => 'action',
                            'action' => [
                                'type' => 'postback',
                                'label' => '女性',
                                'data' => "action=profile&type=sex&id=2",
                                'displayText' => '女性'
                                ]
                            ],
                        ]
                    ]
                ]   
            ]
        ]);
    }

    public function atherMessage($event)
    {
        return json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' => '恐れ入りますが送信されたメッセージには対応しておりません。',
                ]
            ]
        ]);
    }

    private function profileRegist($event, $channelAccessToken)
    {
        $userDao = new UsersDao();
        $userInfo = $userDao->getUserByLineId($event['source']['userId']);
        $user = new Users();
        $user->id = $userInfo['id'];
        $user->occupation = $data['occupation'] ?? $userInfo['occupation'];
        $user->sex = $data['sex'] ?? $userInfo['sex'];
        $userDao->update($user);
        $text = '登録完了しました。';

        // 完了メッセージ送信
        $url = 'https://api.line.me/v2/bot/message/reply'; // リプライ
        $ch = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer {$channelAccessToken}"
        );
        $data = json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $text,                            
                ]
            ]
        ]);
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に

        curl_exec($ch);
        curl_close($ch);
    }

    private function eventDetail($event, $channelAccessToken, $data)
    {
        // イベントの詳細情報を表示する
        $gameInfoDao = new GameInfoDao();
        $gameInfo = $gameInfoDao->selectById($data['id']);
        $text = "イベント詳細\n";
        $text .= "イベント：{$gameInfo['title']}\n";
        $text .= "日付：{$gameInfo['game_date']}\n";
        $text .= "開始時刻：{$gameInfo['start_time']}\n";
        $text .= "場所：{$gameInfo['place']}\n";
        $text .= "人数上限：{$gameInfo['limit_number']}人\n";
        $text .= "参加予定：{$gameInfo['participants_number']}人\n";
        $text .= "詳細：{$gameInfo['detail']}\n";
        $text .= "\n";
        if($data['mode'] === 'reserve') {
            if($gameInfo['limit_number'] <= $gameInfo['participants_number']) {
                $text .= "予約しますか？（キャンセル待ち）";
            } else {
                $text .= "予約しますか？";
            }
        } elseif($data['mode'] === 'cancel') {
            $text .= "キャンセルしますか？";
        }

        $url = 'https://api.line.me/v2/bot/message/reply'; // リプライ

        $ch = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer {$channelAccessToken}"
        );
        $data = json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $text,                            
                    'quickReply' => [
                        'items' =>  [
                            [
                                'type' => 'action',
                                'action' => [
                                    'type' => 'postback',
                                    'label' => 'はい',
                                    'data' => "action={$data['mode']}&id={$gameInfo['id']}",
                                    'displayText' => 'はい'
                                ]
                            ],
                            [
                                'type' => 'action',
                                'action' => [
                                    'type' => 'postback',
                                    'label' => 'いいえ',
                                    'data' => "action=no&id={$gameInfo['id']}",
                                    'displayText' => 'いいえ'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に

        curl_exec($ch);
        curl_close($ch);
    }

    private function eventReserveOrCancel($event, $channelAccessToken, $data)
    {
        $eventService = new EventService();
        $userDao = new UsersDao();
        $gameInfoDao = new GameInfoDao();
        $gameInfo = $gameInfoDao->selectById((int)$data['id']);
        $user = $userDao->getUserByLineId($event['source']['userId']);
        $participant = new Participant();
        $participant->gameId = $data['id'];
        $participant->occupation = $user['occupation'];
        $participant->sex = $user['sex'];
        $participant->name = $user['name'];
        $participant->email = $user['email'] ?? '';
        $participant->tel = $user['tel'];
        $participant->remark = $user['remark'];
        $participant->lineId = $user['line_id'];

        if(isEmpty($user['occupation']) || isEmpty($user['sex'])) {
            $text = 'プロフィールに未設定の項目があるため更新できません。プロフィール設定から設定を行ってください。';
        } else {
            if($data['action'] === 'reserve') {
                $eventService->oneParticipantRegist($participant, [], EventService::MODE_LINE);
                $lineApi = new LineApi();
                $text = $lineApi->createReservationMessage($gameInfo['title'], $gameInfo['game_date'], $gameInfo['start_time']);
            } elseif ($data['action'] === 'cancel') {
                $eventService->cancelComplete($participant, '', $user['id'], EventService::MODE_LINE);
                $lineApi = new LineApi();
                $text = $lineApi->createCancelMessage($gameInfo['title'], $gameInfo['game_date']);
            }
        }
        // 完了メッセージ送信
        $url = 'https://api.line.me/v2/bot/message/reply'; // リプライ
        $ch = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer {$channelAccessToken}"
        );
        $data = json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $text,                            
                ]
            ]
        ]);
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に

        curl_exec($ch);
        curl_close($ch);
    }
}
