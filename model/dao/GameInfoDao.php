<?php

require_once(dirname(__FILE__).'/OpenCourtPDO.php');

class GameInfoDao {

    public function getGameInfoId($date) {
        $pdo = new OpenCourtPDO();
        $sql = 'select id from game_info where game_date = :gameDate';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':gameDate', $date);
        $prepare->execute();

        $result = $prepare->fetch();
        return $result['id'];
    }

    public function getGameInfo($id) {
        $pdo = new OpenCourtPDO();
        $sql = 'select * from game_info where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id);
        $prepare->execute();

        return $prepare->fetch();
    }

    public function getGameInfoList($year, $month) {
        $pdo = new OpenCourtPDO();
        $sql = "select * from game_info where date_part('year', game_date) = :year and date_part('month', game_date) = :month";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':year', $year);
        $prepare->bindValue(':month', $month);
        $prepare->execute();

        return $prepare->fetchAll();
    }
}