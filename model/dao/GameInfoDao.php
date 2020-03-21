<?php

require_once(dirname(__FILE__).'/OpenCourtPDO.php');
require_once(dirname(__FILE__).'/DetailDao.php');

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
        // // PostgreSQL用
        // $sql = "select * from game_info where date_part('year', game_date) = :year and date_part('month', game_date) = :month";
        // MySQL用
        $sql = "select * from game_info where year(game_date) = :year and month(game_date) = :month";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':year', $year);
        $prepare->bindValue(':month', $month);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    public function getGameInfoListByDate($date) {
        $pdo = new OpenCourtPDO();
        $sql = "select * from game_info where game_date = :date";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':date', $date);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    public function insert(GameInfo $gameinfo) {
        $pdo = new OpenCourtPDO();
        $sql = 'insert into game_info (title, game_date, start_time, end_time, place, detail) 
            values(:title, :game_date, :start_time, :end_time, :place, :detail)';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':title', $gameinfo->title, PDO::PARAM_STR);
        $prepare->bindValue(':game_date', $gameinfo->gameDate, PDO::PARAM_STR);
        $prepare->bindValue(':start_time', $gameinfo->startTime, PDO::PARAM_STR);
        $prepare->bindValue(':end_time', $gameinfo->endTime, PDO::PARAM_STR);
        $prepare->bindValue(':place', $gameinfo->place, PDO::PARAM_STR);
        $prepare->bindValue(':detail', $gameinfo->detail, PDO::PARAM_STR);
        $prepare->execute();
    }

    public function update(GameInfo $gameinfo) {
        $pdo = new OpenCourtPDO();
        $sql = 'update game_info set title = :title
        , game_date = :game_date, start_time = :start_time, end_time = :end_time, place = :place, detail = :detail
        where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $gameinfo->id, PDO::PARAM_INT);
        $prepare->bindValue(':title', $gameinfo->title, PDO::PARAM_STR);
        $prepare->bindValue(':game_date', $gameinfo->gameDate, PDO::PARAM_STR);
        $prepare->bindValue(':start_time', $gameinfo->startTime, PDO::PARAM_STR);
        $prepare->bindValue(':end_time', $gameinfo->endTime, PDO::PARAM_STR);
        $prepare->bindValue(':place', $gameinfo->place, PDO::PARAM_STR);
        $prepare->bindValue(':detail', $gameinfo->detail, PDO::PARAM_STR);
        $prepare->execute();
    }

    public function delete(int $id){
        $pdo = new OpenCourtPDO();
        // 先に参加者情報を削除しておく
        $detailDao = New DetailDao();
        $detailDao->deleteByGameId($id);
        $sql = "delete from game_info where id = :id";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();

    }
}