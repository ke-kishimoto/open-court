<?php
namespace api;
use dao\SalesDao;
use dao\GameInfoDao;

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

}