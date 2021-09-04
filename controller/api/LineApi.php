<?php
namespace api;
define('LINE_API_URL', 'https://notify-api.line.me/api/notify');
use api\LineApiWebhook;
use dao\ConfigDao;
use dao\ApiLogDao;
use Exception;
use service\LineService;

// LINE通知用
class LineApi 
{

    /**
     * @Route("/reserveNotify")
     */
    public function reserve_notify($participant, $title, $date, $companion = 0)
    {   
        
        if ($participant['occupation'] == '1') {
            $occupation = '社会人';
        } elseif ($participant['occupation'] == '2') {
            $occupation = '大学・専門学校';
        } elseif ($participant['occupation'] == '3') {
            $occupation = '高校生';
        } else {
            $occupation = '未設定';
        }

        if($participant['sex'] == '1') {
            $sex = '男性';
        } elseif($participant['sex'] == '2') {
            $sex = '女性';
        } else {
            $sex = '未設定';
        }

        $message = "予約が入りました\n";
        $message .=  "イベント : " . $title . "\n";
        $message .=  "日付 : " . $date . "\n";
        $message .= "--------------------\n";
        $message .=  "名前 : " . $participant['name'] . "\n";
        $message .=  "職種 : " . $occupation . "\n";
        $message .=  "性別 : " . $sex . "\n";
        $message .=  "連絡先 : " . $participant['email'] . "\n";
        $message .=  "備考 : " . $participant['remark'] . "\n";
        $message .=  "同伴者数 : " . $companion . "\n";
        $message .= "--------------------\n";

        return $this->line_notify($message);
    }

    /**
     * @Route("/cancelNotify")
     */
    public function cancel_notify($name, $title, $date)
    {
        $message = "予約がキャンセルされました\n";
        $message .=  "イベント : " . $title . "\n";
        $message .=  "日付 : " . $date . "\n";
        $message .= "--------------------\n";
        $message .=  "名前 : " . $name . "\n";
        $message .= "--------------------\n";

        return $this->line_notify($message);
    }  

    /**
     * @Route("/multipleReserve")
     */
    public function multiple_reserve($name, int $count) 
    {
        $message = "予約が入りました\n";
        $message .= "{$name}さんが{$count}件のイベントを予約しました";

        return $this->line_notify($message);
    }

    /**
     * @Route("/inquiry")
     */
    public function inquiry($inquiry) 
    {
        $message = "お問い合わせが入りました\n";
        $message .=  "対象イベント : {$inquiry['game_title']}\n";
        $message .=  "名前 : {$inquiry['name']} \n";
        $message .= "連絡先 : {$inquiry['email']} \n";
        $message .= "問い合わせ内容 : {$inquiry['content']} \n";

        return $this->line_notify($message);
    }

    /**
     * @Route("/troubleReport")
     */
    public function troubleReport($troubleReport)
    {
        $categoryName = '';
        if($troubleReport['category'] == 1) {
            $categoryName = '障害・不具合';
        } elseif ($troubleReport['category'] == 2) {
            $categoryName = '要望';
        } else {
            $categoryName = 'その他';
        }
        $message = "不具合報告・要望\n";
        $message .=  "名前 : {$troubleReport['name']} \n";
        $message .=  "カテゴリ : {$categoryName}\n";
        $message .= "タイトル : {$troubleReport['title']} \n";
        $message .= "詳細 : {$troubleReport['content']} \n";

        return $this->line_notify($message);
    }

    // LINE通知用のfunction
    private function line_notify($message) 
    {
        // 連想配列作ってるだけ
        $data = array(
            "message" => $message
        );
        // URL エンコードされたクエリ文字列を生成する
        $data = http_build_query($data, "", "&");

        $configDao = new ConfigDao();

        // いずれはuserIDにする
        $config = $configDao->selectById(1);

        try {

            if(!empty($config)) {
                $options = array(
                    'http'=>array(
                    'method'=>'POST',
                    'header'=>"Authorization: Bearer " . $config['line_token']. "\r\n"
                    . "Content-Type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: ".strlen($data)  . "\r\n" ,
                    'content' => $data
                    )
                );
                $context = stream_context_create($options);
                $resultJson = file_get_contents(LINE_API_URL ,FALSE,$context );
                $resutlArray = json_decode($resultJson,TRUE);
                if( $resutlArray['status'] != 200)  {
                    return false;
                }
                return true;
            }
        } catch(Exception $ex) {
            return false;
        }
    }

    //////////////////////////////
    // LINE ログイン用
    //////////////////////////////

    /**
     * @Route("/getAccessToken")
     */
    public function getAccessToken($code) 
    {
        // config取得
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $config['callback_url'],
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret']
        );

        $url = 'https://api.line.me/oauth2/v2.1/token';

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, TRUE);                          //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);                //受け取ったデータを変数に
        $result = curl_exec($ch);

        // if(curl_errno($ch)){        //curlでエラー発生
        //     $CURLERR .= 'curl_errno：' . curl_errno($ch) . "\n";
        //     $CURLERR .= 'curl_error：' . curl_error($ch) . "\n";
        //     $CURLERR .= '▼curl_getinfo' . "\n";
        //     foreach(curl_getinfo($ch) as $key => $val){
        //         $CURLERR .= '■' . $key . '：' . $val . "\n";
        //     }
        //     // echo nl2br($CURLERR);
        // }
        curl_close($ch);
        return json_decode($result);
    }

    /**
     * @Route("/accessTokenVerify")
     */
    public function accessTokenVerify($accessToken)
    {
        $url = 'https://api.line.me/oauth2/v2.1/verify';
        $ch = curl_init($url);
        $data = array(
            'access_token' => $accessToken,
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);  // ステータスコードを受け取る
        curl_close($ch);

        // if($httpcode === 200) {
        //     return true;
        // } else {
        //     return false;
        // }
        return $result;
    }

    /**
     * @Route("/updateToken")
     */
    public function updateToken($refreshToken)
    {
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
        $url = 'https://api.line.me/oauth2/v2.1/token';
        $ch = curl_init($url);
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        $data = array(
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
        );
        curl_setopt($ch, CURLOPT_POST, TRUE);                          //POSTで送信
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に

        $result = curl_exec($ch);

        curl_close($ch);
        return json_decode($result);
    }

    /**
     * @Route("/tokenVerify")
     */
    public function tokenVerify($idToken)
    {
        // config取得
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);
        $url = 'https://api.line.me/oauth2/v2.1/verify';
        $ch = curl_init($url);
        $data = array(
            'id_token' => $idToken,
            'client_id' => $config['client_id'],
        );
        curl_setopt($ch, CURLOPT_POST, TRUE);                          //POSTで送信
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に

        $result = curl_exec($ch);

        curl_close($ch);
        return json_decode($result);
    }
    
    /**
     * @Route("/getLineProfile")
     */
    public function getLineProfile($accessToken)
    {
        $url = 'https://api.line.me/v2/profile';
        $headers = array(
            "Authorization: Bearer {$accessToken}"
        );
        // var_dump($headers);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        return json_decode($result);
    }

    /**
     * @Route("/getLineProfileByCode")
     */
    public function getLineProfileByCode($code) 
    {
        // config取得
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        // アクセストークンの取得
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $config['callback_url'],
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret']
        );

        $url = 'https://api.line.me/oauth2/v2.1/token';
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, TRUE);                          //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);                //受け取ったデータを変数に
        $result = curl_exec($ch);

        curl_close($ch);
        $response = json_decode($result);

        $accessToken = $response->access_token;
        $idToken = $response->id_token;

        // id tokenの検証
        $response = $this->tokenVerify($idToken);

        // プロフィールの取得
        $url = 'https://api.line.me/v2/profile';
        $headers = array(
            "Authorization: Bearer {$accessToken}"
        );
        // var_dump($headers);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        return json_decode($result);

    }
    //////////////////////////////
    // LINE Messaging
    //////////////////////////////

    /**
     * @Route("/pushMessage")
     */
    public function pushMessage($userId, $msg = '')
    {
        // config取得
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $url = 'https://api.line.me/v2/bot/message/push';
        $ch = curl_init($url);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer {$config['channel_access_token']}"
        );
        $data = json_encode([
            'to' => "{$userId}",
            'messages' => [
                [
                    'type' => 'text',
                    'text' => "{$msg}"
                ],
            ]
        ]);
        curl_setopt($ch, CURLOPT_POST, TRUE); //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に

        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // ステータスコードを受け取る
        curl_close($ch);
        // $response = json_decode($result);

        // ログ出力
        $apiLog = [];
        $apiLog['api_name'] = 'line';
        $apiLog['method_name'] = 'pushMessage';
        $apiLog['request_name'] = 'pushMessage';
        $apiLog['detail'] = '';
        $apiLog['status_code'] = (int)$httpcode;
        $apiLog['result_message'] = $msg;
        $apiLogDao = new ApiLogDao();
        $apiLogDao->insert($apiLog);

        return (int)$httpcode;
    }

    // 予約時のメッセージ作成
    public function createReservationMessage($title, $date, $startTime)
    {
        $message = "下記のイベントで予約が確定しました。\n";
        $message .= "イベント：{$title}\n";
        $message .= "日付：{$date}\n";
        $message .= "開始時間：{$startTime}\n";
        $message .= "\n";
        $message .= "キャンセルの場合はシステム上、またはLINEのメニューから事前にキャンセルをお願いします。\n";
        // $message .= "問い合わせはこのLINEから連絡お願いします。\n";

        return $message;
    }
    // キャンセルのメッセージ作成
    public function createCancelMessage($title, $date)
    {
        $message = "下記の予約をキャンセルしました。\n";
        $message .= "イベント：{$title}\n";
        $message .= "日付：{$date}\n";

        return $message;
    }

    // webhook
    public function webhook()
    {
        // webhookリクエストを受け取る

        $json = file_get_contents("php://input");
        $contents = json_decode($json, true);
        $events = $contents['events'];

        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        // 署名の検証
        // $httpHeaders = getallheaders();
        // $lineSignature = $httpHeaders["X-Line-Signature"];
        // 上でも取得できるが、大文字小文字を区別しないためには下記で取得が無難
        $lineSignature = $_SERVER['HTTP_X_LINE_SIGNATURE'];
        $channelSecret = $config['channel_secret']; // Channel secret string
        $hash = hash_hmac('sha256', $json, $channelSecret, true);
        $signature = base64_encode($hash);
        // Compare x-line-signature request header string and the signature
        if($signature !== $lineSignature) {
            return;
        }

        foreach($events as $event) {
            if ($event['mode'] !== 'active') {
                continue;
            }
            
            $webhook = new LineApiWebhook();

            // 友達追加された場合
            if(isset($event['type']) && $event['type'] === 'follow') {
                $webhook->addFriend($event, $config['channel_access_token']);
            }
            // 友達解除された場合
            if(isset($event['type']) && $event['type'] === 'unfollow') {
                $webhook->unfollow($event);
            }
            
            // メッセージが送信された場合
            if(isset($event['message']) && $event['message']['type'] === 'text') {
                $webhook->receiveMessageText($event, $config['channel_access_token']);
            }
            // クイックリプライから返信があった場合
            if(isset($event['postback'])) {
                $webhook->receivePostback($event, $config['channel_access_token']);
            }
        }

        echo json_encode('{}');
    }

    /**
     * @Route("/getTargetUser")
     */
    public function getTargetUser()
    {
        header('Content-type: application/json; charset= UTF-8');

        $occupation = $_POST['occupation'] ?? '';
        $sex = $_POST['sex'] ?? '';
        $eventId = $_POST['eventId'] ?? '';

        $service = new LineService();
        $users = $service->getTargetUser($occupation, $sex, $eventId);

        echo json_encode($users);
    }

    /**
     * @Route("/sendMessage")
     */
    public function sendMessage()
    {
        session_start();
        header('Content-type: application/json; charset= UTF-8');
        $data = json_decode(file_get_contents('php://input'), true);

        $csrfToken = $data['csrfToken'] ?? '';
        if($_SESSION['csrf_token'] !== $csrfToken) {
            new Exception("CSRFエラー");
        }

        $users = $data['users'] ?? [];
        $message = $data['message'] ?? '';
        foreach($users as $user) {
            $this->pushMessage($user['line_id'], $message);
        }

        try {
            echo json_encode([]);
        } catch(Exception $e) {
            http_response_code(202);
            $data = ['errMsg' => $e->getMessage()];
            echo json_encode($data);
        }

    }
}

