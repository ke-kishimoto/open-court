<?php
namespace service;

use dao\InquiryDao;
use dao\GameInfoDao;
use api\LineApi;
use api\MailApi;

class InquiryService
{
    public function sendInquiry($inquiry)
    {
        $inquiryDao = new InquiryDao();
        $inquiryDao->insert($inquiry);

        $inquiry['game_title'] = '';
        if($inquiry['game_id']) {
            $gameInfoDao = new GameInfoDao();
            $gameInfo = $gameInfoDao->selectById($inquiry['game_id']);
            $inquiry['game_title'] = $gameInfo['title'];
        }

        // LINE管理者にLINE通知
        $lineApi = new LineApi();
        $lineApi->inquiry($inquiry);

        // LINE IDがあればLINEに通知
        if(!empty($inquiry['line_id'])) {
            $msg = '管理者に問い合わせを送信しました。返信があるまで今しばらくお待ちください。';
            $lineApi->pushMessage($inquiry['line_id'], $msg);
        }

        // メールアドレスがあればメール送信
        if(!empty($inquiry['email'])) {
            $mailApi = new MailApi();
            $mailApi->inquiry_mail($inquiry);
        }
    }
}