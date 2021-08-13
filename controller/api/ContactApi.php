<?php
namespace api;

use dao\TroubleReportDao;

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
}