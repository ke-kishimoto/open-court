<?php

namespace api;

use dao\ConfigDao;
use CURLFile;

class LineRichMenuApi 
{
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

        // 画像のアップロード
        // index.phpと同じフォルダに画像ファイルがある必要がある。
        var_dump($this->uploadRichImg($mainMenuId, 'richmenu_main.jpg'));
        var_dump($this->uploadRichImg($profileMenuId, 'richmenu_profile.jpg'));
        var_dump($this->uploadRichImg($eventMenuId, 'richmenu_event.jpg'));

        // デフォルトの設定  // 画像の設定ができないとうまくいかない
        var_dump($this->setDefaultLiMenu($mainMenuId));

        // // エイリアスの削除
        // var_dump($this->deleteLichMenuAiliasId('richmenu-alias-main'));
        // var_dump($this->deleteLichMenuAiliasId('richmenu-alias-profile'));
        // var_dump($this->deleteLichMenuAiliasId('richmenu-alias-event'));

        // // エイリアスの作成 // ここも、画像がアップされていないとうまくいかない
        // var_dump($this->createRichMenuAlias('richmenu-alias-main', $mainMenuId));
        // var_dump($this->createRichMenuAlias('richmenu-alias-profile', $profileMenuId));
        // var_dump($this->createRichMenuAlias('richmenu-alias-event', $eventMenuId));

        // エイリアスの更新
        // メイン
        $this->updateRichMenuAiliasId('richmenu-alias-main', $mainMenuId);
        // プロフィール
        $this->updateRichMenuAiliasId('richmenu-alias-profile', $profileMenuId);
        // イベント
        $this->updateRichMenuAiliasId('richmenu-alias-event', $eventMenuId);
        
    }

    public function getRichMenus()
    {
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $url = "https://api.line.me/v2/bot/richmenu/list";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$config['channel_access_token']}",
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
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $url = "https://api.line.me/v2/bot/richmenu/{$richMenuId}";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$config['channel_access_token']}",
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
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $url = 'https://api.line.me/v2/bot/richmenu';
        $ch = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer {$config['channel_access_token']}"
        );
        // $data = $this->mainMenu(); // 作成済み
        // $data = $this->profileMenu(); // 作成済み
        // $data = $this->eventMenu(); // 

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
            'chatBarText' => 'メインメニュー',
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
                    'uri' => 'https://opencourt.eventmanc.com/',
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
                    'label' => 'コンタクト',
                    'richMenuAliasId' => 'richmenu-alias-contact',
                    'data' => 'richmenu-changed-to-contact',
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
                    'label' => 'プロフィール',
                    'richMenuAliasId' => 'richmenu-alias-profile',
                    'data' => 'richmenu-changed-to-profile',
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
            'chatBarText' => 'イベントメニュー',
            'areas' => [
                [
                'bounds' => [
                    "x" => 0,
                    "y" => 0,
                    "width" => 1250,
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
                    "x" => 1251,
                    "y" => 0,
                    "width" => 1250,
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
                    "x" => 0,
                    "y" => 844,
                    "width" => 1250,
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
                    "x" => 1251,
                    "y" => 844,
                    "width" => 1250,
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
                    "width" => 1250,
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
                    "x" => 1251,
                    "y" => 0,
                    "width" => 1250,
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
                    "x" => 0,
                    "y" => 844,
                    "width" => 1250,
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
                    "x" => 1251,
                    "y" => 844,
                    "width" => 1250,
                    "height" => 843,
                ],
                'action' => [
                    'type' => 'message',
                    'label' => 'プロフィール確認',
                    'text' => 'プロフィール確認',
                ],
            ],
            ]
        ]);
    }

    private function firstMenu()
    {
        return json_encode([
            'size' => [
                "width" => 800,
                "height" => 540,
            ],
            'selected' => false,
            'name' => 'first menu',
            'chatBarText' => 'menu',
            'areas' => [
                [
                'bounds' => [
                    "x" => 0,
                    "y" => 0,
                    "width" => 267,
                    "height" => 270,
                ],
                'action' => [
                    'type' => 'message',
                    'label' => '予約',
                    'text' => '予約',
                ],
            ],
            [
                'bounds' => [
                    "x" => 267,
                    "y" => 0,
                    "width" => 267,
                    "height" => 270,
                ],
                'action' => [
                    'type' => 'message',
                    'label' => 'キャンセル',
                    'text' => 'キャンセル',
                ],
            ],
            [
                'bounds' => [
                    "x" => 534,
                    "y" => 0,
                    "width" => 267,
                    "height" => 270,
                ],
                'action' => [
                    'type' => 'message',
                    'label' => '予約確認',
                    'text' => '予約確認',
                ],
            ],
            [
                'bounds' => [
                    "x" => 0,
                    "y" => 270,
                    "width" => 800,
                    "height" => 270,
                ],
                'action' => [
                    'type' => 'uri',
                    'label' => '予約システム',
                    'text' => 'https://opencourt.eventmanc.com/',
                ],
            ]
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
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $url = "https://api-data.line.me/v2/bot/richmenu/{$richMenuId}/content";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$config['channel_access_token']}",
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
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $url = "https://api.line.me/v2/bot/user/all/richmenu/{$richMenuId}";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$config['channel_access_token']}"
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
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $url = "https://api.line.me/v2/bot/richmenu/alias";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$config['channel_access_token']}",
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
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $url = "https://api.line.me/v2/bot/richmenu/alias/{richMenuAliasId}";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$config['channel_access_token']}",
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
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $url = "https://api.line.me/v2/bot/richmenu/alias/{$richMenuAliasId}";
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer {$config['channel_access_token']}",
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

