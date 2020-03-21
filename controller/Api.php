<?php

define('LINE_API_URL', 'https://notify-api.line.me/api/notify');
// 個人用
define('LINE_API_TOKEN','99FzrFtUEzMpTcOrZtUK3AaoJqqYMoWWTyNOdq5mQHR'); 
// // 開発グループLINE用
// define('LINE_API_TOKEN','SVcGMVbQUmk2xKoiP5PWbSV8tTine4q9BaglYgmB0AY'); 

class Api 
{
    // LINE通知用のfunction
    public function line_notify(Participant $participant, $title, $date){   
        
        $message = "予約が入りました\n";
        $message .=  "イベント : " . $title . "\n";
        $message .=  "日付 : " . $date . "\n";
        $message .= "--------------------\n";
        $message .=  "名前 : " . $participant->name . "\n";
        $message .=  "職種 : " . $participant->occupation . "\n";
        $message .=  "備考 : " . $participant->remark . "\n";
        $message .= "--------------------\n";

        // 連想配列作ってるだけ
        $data = array(
                            "message" => $message
                        );
        // URL エンコードされたクエリ文字列を生成する
        $data = http_build_query($data, "", "&");

        $options = array(
            'http'=>array(
                'method'=>'POST',
                'header'=>"Authorization: Bearer " . LINE_API_TOKEN. "\r\n"
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
}

