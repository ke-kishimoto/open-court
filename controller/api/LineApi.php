<?php
namespace api;
define('LINE_API_URL', 'https://notify-api.line.me/api/notify');
// // 個人用
// define('LINE_API_TOKEN','99FzrFtUEzMpTcOrZtUK3AaoJqqYMoWWTyNOdq5mQHR'); 
// // 開発グループLINE用
// define('LINE_API_TOKEN','SVcGMVbQUmk2xKoiP5PWbSV8tTine4q9BaglYgmB0AY'); 
use entity\Participant;
use entity\Inquiry;
use dao\ConfigDao;
use Exception;

// LINE通知用
class LineApi 
{
    // 個人の予約通知
    public function reserve_notify(Participant $participant, $title, $date, $companion = 0){   
        
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
    public function cancel_notify($participant, $title, $date){
        $message = "予約がキャンセルされました\n";
        $message .=  "イベント : " . $title . "\n";
        $message .=  "日付 : " . $date . "\n";
        $message .= "--------------------\n";
        $message .=  "名前 : " . $participant['name'] . "\n";
        $message .= "--------------------\n";

        return $this->line_notify($message);
    }  

    // 複数人予約
    public function multiple_reserve(Participant $participant, int $count) {
        $message = "予約が入りました\n";
        $message .= "{$participant->name}さんが{$count}件のイベントを予約しました";

        return $this->line_notify($message);
    }

    // お問い合わせ
    public function inquiry(Inquiry $inquiry) {
        $message = "お問い合わせが入りました\n";
        $message .=  "対象イベント : {$inquiry->gameTitle}\n";
        $message .=  "名前 : {$inquiry->name} \n";
        $message .= "連絡先 : {$inquiry->email} \n";
        $message .= "問い合わせ内容 : {$inquiry->content} \n";

        return $this->line_notify($message);
    }

    // 参加人数が上限に達したときの通知
    public function limit_notify($title, $date, $limit, $count) {
        $message = "参加人数が上限に達しました\n";
        $message .=  "イベント : " . $title . "\n";
        $message .=  "日付 : " . $date . "\n";
        $message .=  "上限 : " . $limit . "\n";
        $message .=  "参加人数 : " . $count . "\n";

        return $this->line_notify($message);
    }

    // LINE通知用のfunction
    private function line_notify($message) {
        // 連想配列作ってるだけ
        $data = array(
            "message" => $message
        );
        // URL エンコードされたクエリ文字列を生成する
        $data = http_build_query($data, "", "&");

        $configDao = new ConfigDao();

        // いずれはuserIDにする
        $config = $configDao->getConfig(1);

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
}

