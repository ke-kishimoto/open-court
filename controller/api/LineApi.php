<?php
namespace api;
define('LINE_API_URL', 'https://notify-api.line.me/api/notify');
use entity\Participant;
use entity\Inquiry;
use dao\ConfigDao;
use entity\TroubleReport;
use Exception;

// LINE通知用
class LineApi 
{
    // 個人の予約通知
    public function reserve_notify(Participant $participant, $title, $date, $companion = 0)
    {   
        
        if ($participant->occupation == '1') {
            $occupation = '社会人';
        } elseif ($participant->occupation == '2') {
            $occupation = '大学・専門学校';
        } elseif ($participant->occupation == '3') {
            $occupation = '高校生';
        }

        if($participant->sex == '1') {
            $sex = '男性';
        } else {
            $sex = '女性';
        }

        $message = "予約が入りました\n";
        $message .=  "イベント : " . $title . "\n";
        $message .=  "日付 : " . $date . "\n";
        $message .= "--------------------\n";
        $message .=  "名前 : " . $participant->name . "\n";
        $message .=  "職種 : " . $occupation . "\n";
        $message .=  "性別 : " . $sex . "\n";
        $message .=  "連絡先 : " . $participant->email . "\n";
        $message .=  "備考 : " . $participant->remark . "\n";
        $message .=  "同伴者数 : " . $companion . "\n";
        $message .= "--------------------\n";

        return $this->line_notify($message);
    }

    // キャンセル通知
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

    // 複数人予約
    public function multiple_reserve($name, int $count) 
    {
        $message = "予約が入りました\n";
        $message .= "{$name}さんが{$count}件のイベントを予約しました";

        return $this->line_notify($message);
    }

    // お問い合わせ
    public function inquiry(Inquiry $inquiry) 
    {
        $message = "お問い合わせが入りました\n";
        $message .=  "対象イベント : {$inquiry->gameTitle}\n";
        $message .=  "名前 : {$inquiry->name} \n";
        $message .= "連絡先 : {$inquiry->email} \n";
        $message .= "問い合わせ内容 : {$inquiry->content} \n";

        return $this->line_notify($message);
    }

    // 不具合・要望報告
    public function troubleReport(TroubleReport $troubleReport)
    {
        $categoryName = '';
        if($troubleReport->category == 1) {
            $categoryName = '障害・不具合';
        } elseif ($troubleReport->category == 2) {
            $categoryName = '要望';
        } else {
            $categoryName = 'その他';
        }
        $message = "不具合報告・要望\n";
        $message .=  "名前 : {$troubleReport->name} \n";
        $message .=  "カテゴリ : {$categoryName}\n";
        $message .= "タイトル : {$troubleReport->title} \n";
        $message .= "詳細 : {$troubleReport->content} \n";

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
    public function getAccessToken($code) 
    {
        // config取得
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $CURLERR = NULL;

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => 'https://opencourt.eventmanc.com/',
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

        if(curl_errno($ch)){        //curlでエラー発生
            $CURLERR .= 'curl_errno：' . curl_errno($ch) . "\n";
            $CURLERR .= 'curl_error：' . curl_error($ch) . "\n";
            $CURLERR .= '▼curl_getinfo' . "\n";
            foreach(curl_getinfo($ch) as $key => $val){
                $CURLERR .= '■' . $key . '：' . $val . "\n";
            }
            // echo nl2br($CURLERR);
        }
        curl_close($ch);
        return json_decode($result);
    }

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

        $result = curl_exec($ch);

        curl_close($ch);
        return json_decode($result);
    }
    
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

    // アクセストークンの取得からプロフィールの取得まで
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
            'redirect_uri' => 'https://opencourt.eventmanc.com/',
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
        var_dump($headers);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に
        $result = curl_exec($ch);
        return json_decode($result);

    }
}

