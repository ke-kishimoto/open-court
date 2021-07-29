<?php
namespace service;

use dao\InquiryDao;
use dao\GameInfoDao;
use entity\Inquiry;
use api\LineApi;
use api\MailApi;

class InquiryService
{
    public function sendInquiry(Inquiry $inquiry)
    {
        $inquiryDao = new InquiryDao();
        $inquiryDao->insert($inquiry);

        $inquiry->gameTitle = '';
        if($inquiry->gameId) {
            $gameInfoDao = new GameInfoDao();
            $gameInfo = $gameInfoDao->selectById($inquiry->gameId);
            $inquiry->gameTitle = $gameInfo['title'];
        }

        // LINE管理者にLINE通知
        $lineApi = new LineApi();
        $lineApi->inquiry($inquiry);

        // LINE IDがあればLINEに通知
        if(!empty($inquiry->lineId)) {
            $msg = '管理者に問い合わせを送信しました。返信があるまで今しばらくお待ちください。';
            $lineApi->pushMessage($inquiry->lineId, $msg);
        }

        // メールアドレスがあればメール送信
        if(!empty($inquiry->email)) {
            $mailApi = new MailApi();
            $mailApi->inquiry_mail($inquiry);
        }
    }
}