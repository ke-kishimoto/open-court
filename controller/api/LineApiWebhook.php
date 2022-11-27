<?php
namespace api;

use api\LineApi;
use dao\GameInfoDao;
use dao\DetailDao;
use dao\UsersDao;
use dao\ApiLogDao;
use service\EventService;

class LineApiWebhook
{
    const QUICK_REPLY_NUM = 13;  // クイックリプライできるのが最大13件らしい
    const CAROUSEL_NUM = 10; // カルーセルは最大10件らしい

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
        if($text === '予約' || $text === 'キャンセル' || $text === '予約確認') {
            $data = $this->eventSelect($event, $text);
        } elseif($text === '職種') {
            $data = $this->occupationSelect($event);
        } elseif($text === '性別') {
            $data = $this->genderSelect($event);
        } elseif($text === 'プロフィール確認') {
            $data = $this->profileConfirmation($event);
        } else {
            $data = $this->atherMessage($event);
        }
        // リクエストの送信
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = json_decode(curl_exec($ch));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // ステータスコードを受け取る
        curl_close($ch);

        // ログ出力
        $apiLog = [];
        $apiLog['api_name'] = 'line';
        $apiLog['method_name'] = 'receiveMessageText';
        $apiLog['request_name'] = 'bot/message/reply';
        $apiLog['detail'] = $text;
        $apiLog['status_code'] = (int)$httpcode;
        $apiLog['result_message'] = isset($result->message) ? $result->message : '';
        $apiLogDao = new ApiLogDao();
        $apiLogDao->insert($apiLog);
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
            $this->profileRegist($event, $channelAccessToken, $data);
        } elseif(isset($data['action']) && $data['action'] === 'select') {
            $this->eventDetail($event, $channelAccessToken, $data);
        } elseif(isset($data['action']) && ($data['action'] === 'reserve' || $data['action'] === 'cancel')) {
            $this->eventReserveOrCancel($event, $channelAccessToken, $data);
        }
    }

    // 友達追加された時の処理
    public function addFriend($event, $channelAccessToken)
    {
        $userDao = new UsersDao();
        $user = $userDao->getUserByLineId($event['source']['userId']);
        if(!$user) {
            // ユーザーが存在しない場合は登録する
            $user = [];
            $user['admin_flg'] = 0;
            $user['line_id'] = $event['source']['userId'];

            // プロフィール取得
            $url = "https://api.line.me/v2/bot/profile/{$event['source']['userId']}"; // リプライ
            $ch = curl_init($url);
            $headers = array(
                "Authorization: Bearer {$channelAccessToken}"
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
            $result = json_decode(curl_exec($ch));
            $user['name'] = isset($result->displayName) ? $result->displayName : '';
            $userDao->insert($user);
        }
    }
    // ブロックされた時の処理
    public function unfollow($event)
    {
        $userDao = new UsersDao();
        $user = $userDao->getUserByLineId($event['source']['userId']);
        if($user) {
            // ユーザーを削除する
            $userDao->delete($user['id']);
        }
    }

    // イベント選択時 カルーセルVer
    private function eventSelect($event, $text)
    {
        if($text === '予約') {
            $gameInfoDao = new GameInfoDao();
            $gameInfoList = $gameInfoDao->getGameInfoListByAfterDate(date('Y-m-d'), '', $event['source']['userId']);
            if(!$gameInfoList) {
                return json_encode([
                    'replyToken' => "{$event['replyToken']}",
                    'messages' => [
                        [
                            'type' => 'text',
                            'text' => '開催予定のイベントはありません。',
                        ]
                    ]
                ]);
            }
            $mode = 'reserve';
        } elseif($text === 'キャンセル' || $text === '予約確認') {
            $detailDao = new DetailDao();
            $gameInfoList = $detailDao->getEventListByLineId($event['source']['userId'], date('Y-m-d'));
            if(!$gameInfoList) {
                return json_encode([
                    'replyToken' => "{$event['replyToken']}",
                    'messages' => [
                        [
                            'type' => 'text',
                            'text' => '予約されたイベントはありません。',
                        ]
                    ]
                ]);
            }
            if($text === 'キャンセル') {
                $mode = 'cancel';
            } elseif($text === '予約確認') {
                $mode = 'confirm';
            }
        } 
        $columns = [];
        foreach($gameInfoList as $gameInfo) {

            // カルーセルVer
            $columns[] = [
                "text" => $this->getEventInfoShort($gameInfo),
                "actions" => [
                    [
                    'type' => 'postback',
                    'label' => "詳細確認",
                    'data' => "action=select&mode={$mode}&id={$gameInfo['id']}",
                    'displayText' => "詳細確認"
                    ]
                ],
            ];
            if(count($columns) >= self::CAROUSEL_NUM) {
                break;
            }
        }

        // 応答メッセージを返す // カルーセルVer
        return json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'template',
                    "altText" => "this is a carousel template",
                    'template' => [
                        "type" => "carousel",
                        "columns" => $columns
                    ]
                ]
            ]
        ]);
    }

    


    private function profileConfirmation($event)
    {
        $userDao = new UsersDao();
        $user = $userDao->getUserByLineId($event['source']['userId']);
        $text = "表示名：{$user['name']}\n";
        if($user['occupation'] == '1') {
            $text .= "職種：社会人\n";
        } elseif($user['occupation'] == '2') {
            $text .= "職種：学生\n";
        } elseif($user['occupation'] == '3') {
            $text .= "職種：高校生\n";
        } else {
            $text .= "職種：未設定\n";
        }
        if($user['sex'] == '1') {
            $text .= "性別：男性";
        } elseif($user['sex'] == '2') {
            $text .= "性別：女性";
        } else {
            $text .= "性別：未設定";
        }

        return json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $text,
                ]
            ]
        ]);

    }

    private function addDouhan($event, $channelAccessToken, $id)
    {

        $url = 'https://api.line.me/v2/bot/message/reply'; // リプライ
        $ch = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer {$channelAccessToken}"
        );

        $body = json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => 
            [
                [
                    "type" => "template",
                    "altText" => "同伴者選択",
                    "template"=> 
                    [
                        "type" => "buttons",
                        "text" => "同伴者の人数を選択してください。",
                        "actions" => 
                        [
                            [
                                'type' => 'postback',
                                'label' => '1人',
                                'data' => "action=reserve&id={$id}&douhan=yes&num=1",
                                'displayText' => '1人'
                            ],
                            [
                                'type' => 'postback',
                                'label' => '2人',
                                'data' => "action=reserve&id={$id}&douhan=yes&num=2",
                                'displayText' => '2人'
                            ],
                            [
                                'type' => 'postback',
                                'label' => '3人',
                                'data' => "action=reserve&id={$id}&douhan=yes&num=3",
                                'displayText' => '3人'
                            ],
                            // [
                            //     'type' => 'postback',
                            //     'label' => '4人',
                            //     'data' => "action=reserve&id={$id}&douhan=yes&num=4",
                            //     'displayText' => '4人'
                            // ],
                            [
                                'type' => 'postback',
                                'label' => '追加しない',
                                'data' => "action=reserve&id={$id}&douhan=yes&num=7",
                                'displayText' => '追加しない'
                            ],
                        ]
                    ]
                ]
            ]
        ]);

        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($body)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = json_decode(curl_exec($ch));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // ステータスコードを受け取る
        curl_close($ch);

        // ログ出力
        $apiLog = [];
        $apiLog['api_name'] = 'line';
        $apiLog['method_name'] = 'addDouhan';
        $apiLog['request_name'] = 'bot/message/reply';
        $apiLog['status_code'] = (int)$httpcode;
        $apiLog['result_message'] = isset($result->message) ? $result->message : '';
        $apiLogDao = new ApiLogDao();
        $apiLogDao->insert($apiLog);
    }

    // 職種選択 ボタンテンプレートVer
    private function occupationSelect($event) 
    {
        return json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => 
            [
                [
                    "type" => "template",
                    "altText" => "職種選択",
                    "template"=> 
                    [
                        "type" => "buttons",
                        "text" => "職種を選択してください。（高校生以下の場合は高校生を選択してください）",
                        "actions" => 
                        [
                            [
                                'type' => 'postback',
                                'label' => '社会人',
                                'data' => "action=profile&type=occupation&id=1",
                                'displayText' => '社会人'
                            ],
                            [
                                'type' => 'postback',
                                'label' => '学生（大学・専門学校）',
                                'data' => "action=profile&type=occupation&id=2",
                                'displayText' => '学生（大学・専門学校）'
                            ],
                            [
                                'type' => 'postback',
                                'label' => '高校生',
                                'data' => "action=profile&type=occupation&id=3",
                                'displayText' => '高校生'
                            ],
                            [
                                'type' => 'postback',
                                'label' => '設定しない',
                                'data' => "action=profile&type=occupation&id=0",
                                'displayText' => '設定しない'
                            ],
                        ]
                    ]
                ]
            ]
        ]);
    }

    

    // 性別の選択　ボタンテンプレートVer
    private function genderSelect($event)
    {
        return json_encode([
            'replyToken' => "{$event['replyToken']}",
            'messages' => 
            [
                [
                    "type" => "template",
                    "altText" => "性別設定",
                    "template"=> 
                    [
                        "type" => "buttons",
                        "text" => "性別を選択してください。",
                        "actions" => 
                        [
                            [
                                'type' => 'postback',
                                'label' => '男性',
                                'data' => "action=profile&type=sex&id=1",
                                'displayText' => '男性'
                            ],
                            [
                                'type' => 'postback',
                                'label' => '女性',
                                'data' => "action=profile&type=sex&id=2",
                                'displayText' => '女性'
                            ],
                            [
                                'type' => 'postback',
                                'label' => '設定しない',
                                'data' => "action=profile&type=sex&id=0",
                                'displayText' => '設定しない'
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

    private function profileRegist($event, $channelAccessToken, $data)
    {
        if($data['id'] == 0) {
            return;
        }

        $userDao = new UsersDao();
        $userInfo = $userDao->getUserByLineId($event['source']['userId']);
        // $user = new Users();
        $user = [];
        $user['id'] = $userInfo['id'];
        if($data['type'] === 'occupation' && $data['id'] != 0) {
            $user['occupation'] = $data['id'];
        } else {
            $user['occupation'] = $userInfo['occupation'];
        }
        if($data['type'] === 'sex' && $data['id'] != 0) {
            $user['sex'] = $data['id'];
        } else {
            $user['sex'] = $userInfo['sex'];
        }
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
        $info = $this->getEventInfoLong($gameInfo);

        if($data['mode'] === 'reserve') {
            if($gameInfo['limit_number'] <= $gameInfo['participants_number']) {
                $text = "予約しますか？（キャンセル待ち）";
            } else {
                $text = "予約しますか？";
            }
        } elseif($data['mode'] === 'cancel') {
            $text = "キャンセルしますか？";
        }

        $url = 'https://api.line.me/v2/bot/message/reply'; // リプライ

        $ch = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer {$channelAccessToken}"
        );
        // クイックリプライVer
        // $data = json_encode([
        //     'replyToken' => "{$event['replyToken']}",
        //     'messages' => [
        //         [
        //             'type' => 'text',
        //             'text' => $text,                            
        //             'quickReply' => [
        //                 'items' =>  [
        //                     [
        //                         'type' => 'action',
        //                         'action' => [
        //                             'type' => 'postback',
        //                             'label' => 'はい',
        //                             'data' => "action={$data['mode']}&id={$gameInfo['id']}",
        //                             'displayText' => 'はい'
        //                         ]
        //                     ],
        //                     [
        //                         'type' => 'action',
        //                         'action' => [
        //                             'type' => 'postback',
        //                             'label' => 'いいえ',
        //                             'data' => "action=no&id={$gameInfo['id']}",
        //                             'displayText' => 'いいえ'
        //                         ]
        //                     ]
        //                 ]
        //             ]
        //         ]
        //     ]
        // ]);

        $body = [
            'replyToken' => "{$event['replyToken']}",
            'messages' => 
                [
                    [
                        'type' => 'text',
                        'text' => $info,
                    ]
                ]
            ];

        if($data['mode'] === 'reserve') {
            $body['messages'][] = [
                "type" => "template",
                "altText" => "予約確認",
                "template"=> 
                [
                    "type" => "buttons",
                    "text" => "{$text}",
                    "actions" => 
                    [
                        [
                            'type' => 'postback',
                            'label' => '予約する',
                            'data' => "action={$data['mode']}&id={$gameInfo['id']}&douhan=no",
                            'displayText' => '予約する'
                        ],
                        [
                            'type' => 'postback',
                            'label' => '同伴者を追加して予約する',
                            'data' => "action={$data['mode']}&id={$gameInfo['id']}&douhan=yes",
                            'displayText' => '同伴者を追加して予約する'
                        ],
                        [
                            'type' => 'postback',
                            'label' => '予約しない',
                            'data' => "action=no&id={$gameInfo['id']}",
                            'displayText' => '予約しない'
                        ],
                    ]
                ]
                    ];
        } elseif($data['mode'] === 'cancel') {
            $body['messages'][] = [
                'type' => 'template',
                'altText' => 'キャンセル確認',                            
                'template' => [
                    "type" => "confirm",
                    "text" => $text,
                    "actions" => [
                        [
                            'type' => 'postback',
                            'label' => 'する',
                            'data' => "action={$data['mode']}&id={$gameInfo['id']}",
                            'displayText' => 'する'
                        ],
                        [
                            'type' => 'postback',
                            'label' => 'しない',
                            'data' => "action=no&id={$gameInfo['id']}",
                            'displayText' => 'しない'
                        ]
                    ],
                ]
            ];
        }

        // 確認テンプレートVer
        // $body = json_encode([
        //     'replyToken' => "{$event['replyToken']}",
        //     'messages' => [
        //         [
        //             'type' => 'text',
        //             'text' => $info,
        //         ],
        //         [
        //             'type' => 'template',
        //             'altText' => 'this is a confirm template',                            
        //             'template' => [
        //                 "type" => "confirm",
        //                 "text" => $text,
        //                 "actions" => [
        //                     [
        //                         'type' => 'postback',
        //                         'label' => 'はい',
        //                         'data' => "action={$data['mode']}&id={$gameInfo['id']}",
        //                         'displayText' => 'はい'
        //                     ],
        //                     [
        //                         'type' => 'postback',
        //                         'label' => 'いいえ',
        //                         'data' => "action=no&id={$gameInfo['id']}",
        //                         'displayText' => 'いいえ'
        //                     ]
        //                 ],
        //             ]
        //         ]
        //     ]
        // ]);
        $body = json_encode($body);
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($body)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に

        curl_exec($ch);
        curl_close($ch);
    }

    private function eventReserveOrCancel($event, $channelAccessToken, $data)
    {
        $eventService = new EventService();
        $userDao = new UsersDao();
        $gameInfoDao = new GameInfoDao();
        $detailDao = new DetailDao();
        $gameInfo = $gameInfoDao->selectById((int)$data['id']);
        $user = $userDao->getUserByLineId($event['source']['userId']);
        $participant = [];
        $participant['game_id'] = $data['id'];
        $participant['occupation'] = $user['occupation'];
        $participant['sex'] = $user['sex'];
        $participant['name'] = $user['name'];
        $participant['email'] = $user['email'] ?? '';
        $participant['tel'] = $user['tel'];
        $participant['remark'] = $user['remark'];
        $participant['line_id'] = $user['line_id'];

        if(empty($user['occupation']) || empty($user['sex'])) {
            $text = 'プロフィールに未設定の項目があるため更新できません。プロフィール設定から設定を行ってください。';
        } else {
            if($data['action'] === 'reserve') {
                // 予約の場合
                $result = $detailDao->existsCheck($gameInfo['id'], '', $user['line_id']);
                if($result['result']) {
                    $text = '既に予約済みのため予約できません。';
                } elseif ($user['black_flg'] == '9') {
                  $text = 'アカウントがロックされているため予約できません。解除されるまで待つか管理者に問い合わせてください。';
                } else {
                    if($data['douhan'] === 'no') {
                    $eventService->oneParticipantRegist($participant, [] , EventService::MODE_LINE);
                        $lineApi = new LineApi();
                        $text = $lineApi->createReservationMessage($gameInfo['title'], $gameInfo['game_date'], $gameInfo['start_time']);
                    } elseif($data['douhan'] === 'yes' && !isset($data['num'])) {
                        $this->addDouhan($event, $channelAccessToken, $data['id']);
                        return ;
                    } elseif($data['douhan'] === 'yes' && isset($data['num'])) {
                        $num = (int)$data['num'];
                        $companion = [];
                        for($i = 1; $i <= $num; $i++) {
                            $companion[] = 
                            [
                                'occupation' => $participant['occupation'],
                                'sex' => $participant['sex'],
                                'name' => "同伴{$i}({$participant['name']})"
                            ];
                        }
                        $eventService->oneParticipantRegist($participant, $companion , EventService::MODE_LINE );
                        $lineApi = new LineApi();
                        $text = $lineApi->createReservationMessage($gameInfo['title'], $gameInfo['game_date'], $gameInfo['start_time']);
                    }
                }
            } elseif ($data['action'] === 'cancel') {
                $msg = $eventService->cancelComplete($participant, '', $user['id'] , EventService::MODE_LINE );
                $lineApi = new LineApi();
                if(!empty($msg)) {
                    $text = $msg;
                } else {  
                    $text = $lineApi->createCancelMessage($gameInfo['title'], $gameInfo['game_date']);
                }
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

    private function getEventInfoShort($gameInfo)
    {
        return "イベント詳細\n イベント：{$gameInfo['title']}\n 日付：{$gameInfo['game_date']}\n 開始時刻：{$gameInfo['start_time']}\n 場所：{$gameInfo['place']}";
    }

    private function getEventInfoLong($gameInfo)
    {
        $text = "イベント詳細\n";
        $text .= "イベント：{$gameInfo['title']}\n";
        $text .= "日付：{$gameInfo['game_date']}\n";
        $text .= "開始時刻：{$gameInfo['start_time']}\n";
        $text .= "場所：{$gameInfo['place']}\n";
        $text .= "人数上限：{$gameInfo['limit_number']}人\n";
        $text .= "参加予定：{$gameInfo['participants_number']}人\n";
        $text .= "参加費：「社会人：{$gameInfo['price1']}」「学生：{$gameInfo['price2']}」「高校生：{$gameInfo['price3']}」\n";
        $text .= "詳細：{$gameInfo['detail']}\n";
        return $text;
    }

    // private function genderSelectQuick($event) {
    //     return json_encode([
    //         'replyToken' => "{$event['replyToken']}",
    //         'messages' => [
    //             [
    //                 'type' => 'text',
    //                 'text' =>  '性別を選択してください。',                            
    //                 'quickReply' => [
    //                     'items' =>  [
    //                         [
    //                         'type' => 'action',
    //                         'action' => [
    //                             'type' => 'postback',
    //                             'label' => '男性',
    //                             'data' => "action=profile&type=sex&id=1",
    //                             'displayText' => '男性'
    //                             ]
    //                         ],
    //                         [
    //                         'type' => 'action',
    //                         'action' => [
    //                             'type' => 'postback',
    //                             'label' => '女性',
    //                             'data' => "action=profile&type=sex&id=2",
    //                             'displayText' => '女性'
    //                             ]
    //                         ],
    //                     ]
    //                 ]
    //             ]   
    //         ]
    //     ]);
    // }

    // // 職種選択 クイックリプライVer
    // private function occupationSelectQuick($event) {
    //     return json_encode([
    //         'replyToken' => "{$event['replyToken']}",
    //         'messages' => [
    //             [
    //                 'type' => 'text',
    //                 'text' =>  '職種を選択してください。（高校生以下の場合は高校生を選択してください）',                            
    //                 'quickReply' => [
    //                     'items' => [
    //                         [
    //                         'type' => 'action',
    //                         'action' => [
    //                             'type' => 'postback',
    //                             'label' => '社会人',
    //                             'data' => "action=profile&type=occupation&id=1",
    //                             'displayText' => '社会人'
    //                             ]
    //                         ],
    //                         [
    //                         'type' => 'action',
    //                         'action' => [
    //                             'type' => 'postback',
    //                             'label' => '学生（大学・専門学校）',
    //                             'data' => "action=profile&type=occupation&id=2",
    //                             'displayText' => '学生（大学・専門学校）'
    //                             ]
    //                         ],
    //                         [
    //                         'type' => 'action',
    //                         'action' => [
    //                             'type' => 'postback',
    //                             'label' => '高校生',
    //                             'data' => "action=profile&type=occupation&id=3",
    //                             'displayText' => '高校生'
    //                             ]
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ]);
    // }

    // // イベントセレクト時 クイックリプライVer
    // private function eventSelectQuick($event, $text)
    // {
    //     if($text === '予約') {
    //         $gameInfoDao = new GameInfoDao();
    //         $gameInfoList = $gameInfoDao->getGameInfoListByAfterDate(date('Y-m-d'), '', $event['source']['userId']);
    //         $msg = '予約したいイベントを選択してください。';
    //         $mode = 'reserve';
    //     } else {
    //         $detailDao = new DetailDao();
    //         $gameInfoList = $detailDao->getEventListByLineId($event['source']['userId'], date('Y-m-d'));
    //         $msg = 'キャンセルしたいイベントを選択してください。';
    //         $mode = 'cancel';
    //     }
    //     $items = [];
    //     foreach($gameInfoList as $gameInfo) {
    //         $items[] = [
    //             'type' => 'action', 
    //             'action' => [
    //                 'type' => 'postback',
    //                 'label' => "{$gameInfo['game_date']} {$gameInfo['short_title']}",
    //                 'data' => "action=select&mode={$mode}&id={$gameInfo['id']}",
    //                 'displayText' => "{$gameInfo['game_date']} {$gameInfo['short_title']}"
    //             ]
    //         ];
    //         if(count($items) >= self::QUICK_REPLY_NUM) {
    //             break;
    //         }
    //     }
    //     return json_encode([
    //         'replyToken' => "{$event['replyToken']}",
    //         'messages' => [
    //             [
    //                 'type' => 'text',
    //                 'text' => $msg,                            
    //                 'quickReply' => [
    //                     'items' =>  $items
    //                 ]
    //             ]
    //         ]
    //     ]);
    // }


}
