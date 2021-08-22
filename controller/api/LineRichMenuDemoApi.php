<?php

namespace api;

use dao\ConfigDao;
use CURLFile;

class LineRichMenuDemoApi 
{

    public $channelAccessToken;

    public function __construct()
    {
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
        $this->channelAccessToken = $config['channel_access_token'];
    }

    public function createRichMenuAll()
    {
        // 一旦メニューを全て削除
        $menus = $this->getRichMenus();
        foreach($menus->richmenus as $menu) {
            // echo $menu->richMenuId;
            $this->deleteRichMenu($menu->richMenuId);
        }
        // メニュー作成
        // メインメニュー
        $data = $this->mainMenu();
        $richMenu = $this->createRichMenu($data);
        $mainMenuId = $richMenu->richMenuId; 
        var_dump($mainMenuId);
        // プロフィールメニュー
        $data = $this->profileMenu();
        $richMenu = $this->createRichMenu($data);
        $profileMenuId = $richMenu->richMenuId;
        var_dump($profileMenuId);
        // イベントメニュー
        $data = $this->eventMenu();
        $richMenu = $this->createRichMenu($data);
        $eventMenuId = $richMenu->richMenuId;
        var_dump($eventMenuId);
        // // コンタクトメニュー
        $data = $this->contactMenu();
        $richMenu = $this->createRichMenu($data);
        $contactMenuId = $richMenu->richMenuId;
        var_dump($contactMenuId);


        // 画像のアップロード
        // index.phpと同じフォルダに画像ファイルがある必要がある。
        var_dump($this->uploadRichImg($mainMenuId, 'richmenu_main.png'));
        var_dump($this->uploadRichImg($profileMenuId, 'richmenu_profile.png'));
        var_dump($this->uploadRichImg($eventMenuId, 'richmenu_event.png'));
        var_dump($this->uploadRichImg($contactMenuId, 'richmenu_contact.png'));

        // デフォルトの設定  // 画像の設定ができないとうまくいかない
        var_dump($this->setDefaultLiMenu($mainMenuId));

        // エイリアスの削除
        var_dump($this->deleteLichMenuAiliasId('richmenu-alias-main'));
        var_dump($this->deleteLichMenuAiliasId('richmenu-alias-profile'));
        var_dump($this->deleteLichMenuAiliasId('richmenu-alias-event'));
        var_dump($this->deleteLichMenuAiliasId('richmenu-alias-contact'));

        // 削除してから実行しても、コンフリクトでエラーが出ることがある。。
        // エイリアスの作成 // ここも、画像がアップされていないとうまくいかない
        var_dump($this->createRichMenuAlias('richmenu-alias-main', $mainMenuId));
        var_dump($this->createRichMenuAlias('richmenu-alias-profile', $profileMenuId));
        var_dump($this->createRichMenuAlias('richmenu-alias-event', $eventMenuId));
        var_dump($this->createRichMenuAlias('richmenu-alias-contact', $contactMenuId));

        // // エイリアスの更新 // 作成でコンフリクトのエラーが出た時はここで更新処理を実行
        // $this->updateRichMenuAiliasId('richmenu-alias-main', $mainMenuId);
        // $this->updateRichMenuAiliasId('richmenu-alias-profile', $profileMenuId);
        // $this->updateRichMenuAiliasId('richmenu-alias-event', $eventMenuId);
        // $this->updateRichMenuAiliasId('richmenu-alias-contact', $contactMenuId);
        
    }

    public function getRichMenus()
    {
    
        $url = "https://api.line.me/v2/bot/richmenu/list";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$this->channelAccessToken}",
            "Content-Type: application/json",
        );
       
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    public function deleteRichMenu($richMenuId = '')
    {
        if(empty($richMenuId)) {
            $richMenuId = $_GET['richMenuId'] ?? '';
        }
    
        $url = "https://api.line.me/v2/bot/richmenu/{$richMenuId}";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$this->channelAccessToken}",
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    public function createRichMenu($data) 
    {
    
        $url = 'https://api.line.me/v2/bot/richmenu';
        $ch = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer {$this->channelAccessToken}"
        );
    
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    private function mainMenu()
    {
        return json_encode([
            'size' => [
                "width" => 2500,
                "height" => 1686,
            ],
            'selected' => false,
            'name' => 'main menu',
            'chatBarText' => 'ホーム',
            'areas' => [
                [
                'bounds' => [
                    "x" => 0,
                    "y" => 0,
                    "width" => 1250,
                    "height" => 843,
                ],
                'action' => [
                    'type' => 'uri',
                    'label' => '予約システム',
                    'uri' => 'https://demo.eventmanc.com/',
                ],
                
            ],
            [
                'bounds' => [
                    "x" => 1251,
                    "y" => 0,
                    "width" => 1250,
                    "height" => 843,
                ],
                'action' => [
                    'type' => 'richmenuswitch',
                    'label' => 'アカウント情報',
                    'richMenuAliasId' => 'richmenu-alias-profile',
                    'data' => 'richmenu-changed-to-profile',
                ],
            ],
            [
                'bounds' => [
                    "x" => 0,
                    "y" => 844,
                    "width" => 1250,
                    "height" => 843,
                ],
                'action' => [
                    'type' => 'richmenuswitch',
                    'label' => 'イベント',
                    'richMenuAliasId' => 'richmenu-alias-event',
                    'data' => 'richmenu-changed-to-event',
                ],
            ],
            [
                'bounds' => [
                    "x" => 1251,
                    "y" => 844,
                    "width" => 1250,
                    "height" => 843,
                ],
                'action' => [
                    'type' => 'richmenuswitch',
                    'label' => 'コンタクト',
                    'richMenuAliasId' => 'richmenu-alias-contact',
                    'data' => 'richmenu-changed-to-event',
                ],
            ],
            ]
        ]);
    }

    private function eventMenu()
    {
        return json_encode([
            'size' => [
                "width" => 2500,
                "height" => 1686,
            ],
            'selected' => false,
            'name' => 'event menu',
            'chatBarText' => 'イベント',
            'areas' => [
                [
                    'bounds' => [
                        "x" => 0,
                        "y" => 0,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'uri',
                        'label' => '予約システム',
                        'uri' => 'https://demo.eventmanc.com/',
                    ],  
                ],
                [
                    'bounds' => [
                        "x" => 0,
                        "y" => 844,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'richmenuswitch',
                        'label' => 'ホーム',
                        'richMenuAliasId' => 'richmenu-alias-main',
                        'data' => 'richmenu-changed-to-main',
                    ],  
                ],
                [
                    'bounds' => [
                        "x" => 834,
                        "y" => 0,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => '予約',
                        'text' => '予約',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 834,
                        "y" => 844,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => '一括予約（開発中）',
                        'text' => '一括予約（開発中）',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 1669,
                        "y" => 0,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => '予約確認',
                        'text' => '予約確認',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 1669,
                        "y" => 844,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => 'キャンセル',
                        'text' => 'キャンセル',
                    ],
                    
                ],
            ]
        ]);
    }

    private function profileMenu()
    {
        return json_encode([
            'size' => [
                "width" => 2500,
                "height" => 1686,
            ],
            'selected' => false,
            'name' => 'profile menu',
            'chatBarText' => 'プロフィールメニュー',
            'areas' => [
                [
                    'bounds' => [
                        "x" => 0,
                        "y" => 0,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'uri',
                        'label' => '予約システム',
                        'uri' => 'https://demo.eventmanc.com/',
                    ],  
                ],
                [
                    'bounds' => [
                        "x" => 0,
                        "y" => 844,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'richmenuswitch',
                        'label' => 'メインメニュー',
                        'richMenuAliasId' => 'richmenu-alias-main',
                        'data' => 'richmenu-changed-to-main',
                    ],
                    
                ],
                [
                    'bounds' => [
                        "x" => 834,
                        "y" => 0,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => '職種設定',
                        'text' => '職種',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 834,
                        "y" => 844,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => '性別設定',
                        'text' => '性別',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 1669,
                        "y" => 0,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => 'プロフィール確認',
                        'text' => 'プロフィール確認',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 1669,
                        "y" => 844,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => '通知設定（開発中）',
                        'text' => '通知設定（開発中）',
                    ],
                ],
            ]
        ]);
    }

    private function contactMenu()
    {
        return json_encode([
            'size' => [
                "width" => 2500,
                "height" => 1686,
            ],
            'selected' => false,
            'name' => 'profile menu',
            'chatBarText' => 'コンタクトメニュー',
            'areas' => [
                [
                    'bounds' => [
                        "x" => 0,
                        "y" => 0,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'uri',
                        'label' => '予約システム',
                        'uri' => 'https://demo.eventmanc.com/',
                    ],  
                ],
                [
                    'bounds' => [
                        "x" => 0,
                        "y" => 844,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'richmenuswitch',
                        'label' => 'メインメニュー',
                        'richMenuAliasId' => 'richmenu-alias-main',
                        'data' => 'richmenu-changed-to-main',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 834,
                        "y" => 0,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => '障害報告（開発中）',
                        'text' => '障害報告（開発中）',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 834,
                        "y" => 844,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'message',
                        'label' => 'お問い合わせ',
                        'text' => 'お問い合わせ',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 1669,
                        "y" => 0,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'uri',
                        'label' => 'コンタクト',
                        'uri' => 'https://lin.ee/gi7g67H/',
                    ],
                ],
                [
                    'bounds' => [
                        "x" => 1669,
                        "y" => 844,
                        "width" => 833,
                        "height" => 843,
                    ],
                    'action' => [
                        'type' => 'uri',
                        'label' => 'ネットショップ',
                        'uri' => 'https://lin.ee/gi7g67H/',
                    ],
                ],
            ]
        ]);
    }

    public function uploadRichImg($richMenuId = '', $fileName = '')
    {
        if(empty($richMenuId)) {
            $richMenuId = $_GET['richMenuId'] ?? '';
        }
        if(empty($fileName)) {
            $fileName = $_GET['fileName'] ?? '';
        }
    
        $url = "https://api-data.line.me/v2/bot/richmenu/{$richMenuId}/content";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$this->channelAccessToken}",
            "Content-Type: image/jpeg",
        );

        $cfile = new CURLFile($fileName,'image/jpeg', $fileName);
        // $data = array('image' => $cfile);
        // $data = ['__file' => $fileName];

        // $options[CURLOPT_PUT] = true;
        // $options[CURLOPT_INFILE] = fopen($reqBody['__file'], 'r');
        // $options[CURLOPT_INFILESIZE] = filesize($reqBody['__file']);

        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        // curl_setopt($ch, CURLOPT_PUT, TRUE);  //PUTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_INFILE, fopen($fileName, 'r'));
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($fileName));
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $cfile);  // 処理が終わらない。これではダメ。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
        
    }

    public function deleteLichMenu($lichMenuId)
    {

    }

    // デフォルトメニューの設定
    public function setDefaultLiMenu($richMenuId = '')
    {
        if(empty($richMenuId)) {
            $richMenuId = $_GET['richMenuId'] ?? '';
        }
    
        $url = "https://api.line.me/v2/bot/user/all/richmenu/{$richMenuId}";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$this->channelAccessToken}"
        );
       
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    // エイリアス作成
    public function createRichMenuAlias($richMenuAliasId = '', $richMenuId = '')
    {
        if(empty($richMenuAliasId)) {
            $richMenuAliasId = $_GET['richMenuAliasId'] ?? '';
        }
        if(empty($richMenuId)) {
            $richMenuId = $_GET['richMenuId'] ?? '';
        }
    
        $url = "https://api.line.me/v2/bot/richmenu/alias";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$this->channelAccessToken}",
            "Content-Type: application/json",
        );
        $data = json_encode([
            'richMenuAliasId' => $richMenuAliasId,
            'richMenuId' => $richMenuId
        ]);
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    // エイリアス削除
    public function deleteLichMenuAiliasId($richMenuAliasId = '')
    {
        if(empty($richMenuAliasId)) {
            $richMenuAliasId = $_GET['richMenuAliasId'] ?? '';
        }
    
        $url = "https://api.line.me/v2/bot/richmenu/alias/{$richMenuAliasId}";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$this->channelAccessToken}",
            "Content-Type: application/json",
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");  //POSTで送信    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    // エイリアス更新
    public function updateRichMenuAiliasId($richMenuAliasId = '', $richMenuId = '')
    {
        if(empty($richMenuAliasId)) {
            $richMenuAliasId = $_GET['richMenuAliasId'] ?? '';
        }
        if(empty($richMenuId)) {
            $richMenuId = $_GET['richMenuId'] ?? '';
        }
    
        $url = "https://api.line.me/v2/bot/richmenu/alias/{$richMenuAliasId}";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$this->channelAccessToken}",
            "Content-Type: application/json",
        );
        $data = json_encode([
            'richMenuId' => $richMenuId
        ]);
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }
}

