<?php
namespace api;
use dao\SalesDao;
use dao\GameInfoDao;
use dao\DetailDao;

class SalesApi
{
    public function getMonthSales()
    {
        header('Content-type: application/json; charset= UTF-8');

        $year = $_POST['year'];
        $month = $_POST['month'];

        $salesDao = new SalesDao();
        $eventList = $salesDao->getMonthSales($year, $month);

        echo json_encode($eventList);
    }

    public function updateExpenses()
    {
        header('Content-type: application/json; charset= UTF-8');
    
        $eventList = json_decode(file_get_contents('php://input'), true);
        $GameInfoDao = new GameInfoDao();
        for($i = 0; $i < count($eventList); $i++) {
            $p = $GameInfoDao->selectById((int)($eventList[$i]['game_id']));
            $gameInfo = [];
            $gameInfo['id'] = $p['id'];
            $gameInfo['expenses'] = (int)($eventList[$i]["expenses"]);
            $gameInfo['participantnum'] = (int)($eventList[$i]["cnt"]);
            $gameInfo['amount'] = (int)($eventList[$i]["amount"]);
            $GameInfoDao->update($gameInfo);
        }
        echo json_encode([]);
    }

    public function getYearSales()
    {
        header('Content-type: application/json; charset= UTF-8');

        $salesDao = new SalesDao();
        $year = (int)$_POST['year'] ?? date('Y');
        $salesMonthList = $salesDao->getYearMonthSales($year);

        echo json_encode($salesMonthList);
    }

    public function getAllSales()
    {
        header('Content-type: application/json; charset= UTF-8');

        $salesDao = new SalesDao();
        $salesYearList = $salesDao->getYearSales();

        echo json_encode($salesYearList);
    }

    public function getParticipantList()
    {
        header('Content-type: application/json; charset= UTF-8');

        $salesDao = new SalesDao();
        $gameId = $_POST['gameid'] ?? 0;
        $participantList = $salesDao->getSalesDetail($gameId);

        echo json_encode($participantList);
    }

    public function updateParticipantAmount()
    {
        header('Content-type: application/json; charset= UTF-8');
    
        $participantList = json_decode(file_get_contents('php://input'), true);

        $detailDao = new DetailDao();
        for($i = 0; $i < count($participantList); $i++) {
            $p = $detailDao->selectById((int)($participantList[$i]["id"]));
            $participant = [];
            $participant['id'] = $p['id'];
            $participant['attendance'] = $participantList[$i]["attendance"];
            $participant['amount'] = (int)($participantList[$i]["amount"]);
            $participant['amount_remark'] = $participantList[$i]["amount_remark"];
            $detailDao->update($participant);
        }

        echo json_encode([]);

    }

}