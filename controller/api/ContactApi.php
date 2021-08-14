<?php
namespace api;

use dao\TroubleReportDao;
use service\InquiryService;

class ContactApi
{
    public function sendTroubleReport()
    {
        header('Content-type: application/json; charset= UTF-8');

        if(isset($_POST)) {
            $troubleReportDao = new TroubleReportDao();
            $troubleReport = [];
            $troubleReport['name'] = $_POST['name'];
            $troubleReport['category'] = (int)$_POST['category'];
            $troubleReport['title'] = $_POST['title'];
            $troubleReport['content'] = $_POST['content'];
            $troubleReport['status_flg'] = 0;

            $troubleReportDao->insert($troubleReport);

            // LINE通知用に参加者情報とイベント情報を取得
            $api = new LineApi();
            $api->troubleReport($troubleReport);
        }
        echo json_encode([]);
    }

    public function sendInquiry() {

        $errMsg = '';
        if(isset($_POST)) {
            $gameId = (int)$_POST['game_id'];
            $inquiry = [];
            $inquiry['game_id'] = $gameId;
            $inquiry['name'] = $_POST['name'];
            $inquiry['email'] = $_POST['email'];
            $inquiry['content'] = $_POST['content'];
            $inquiry['status_flg'] = 0;
            if(isset($_SESSION['user'])) {
                $inquiry['line_id'] = $_SESSION['user']['line_id'] ?? '';
            }
            $inquiry['update_date'] = null;
            
            $service = new InquiryService();
            $service->sendInquiry($inquiry);
            

        }
        echo json_encode('{}');
    }
}