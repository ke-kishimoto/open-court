<?php
namespace dao;

require_once(dirname(__FILE__).'/DaoFactory.php');
require_once(dirname(__FILE__).'/DetailDao.php');

use dao\DaoFactory;
use PDO;
use entity\GameInfo;

class GameInfoDao {

    public function getGameInfoId($date) {
        $pdo = DaoFactory::getConnection();
        $sql = 'select id from game_info where game_date = :gameDate';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':gameDate', $date);
        $prepare->execute();

        $result = $prepare->fetch();
        return $result['id'];
    }

    public function getGameInfo($id) {
        $pdo = DaoFactory::getConnection();
        $sql = 'select * from game_info where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id);
        $prepare->execute();

        return $prepare->fetch();
    }

    public function getGameInfoList($year, $month) {
        $pdo = DaoFactory::getConnection();
        // PostgreSQL用
        // $sql = "select * from game_info where date_part('year', game_date) = :year and date_part('month', game_date) = :month";
        // // MySQL用
        $sql = "select * from game_info where year(game_date) = :year and month(game_date) = :month";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':year', $year);
        $prepare->bindValue(':month', $month);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    public function getGameInfoListByDate($date) {
        $pdo = DaoFactory::getConnection();
        $sql = "select * from game_info where game_date = :date";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':date', $date);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    public function insert(GameInfo $gameinfo) {
        $pdo = DaoFactory::getConnection();
        $sql = 'insert into game_info (title, game_date, start_time, end_time, place, limit_number, detail) 
            values(:title, :game_date, :start_time, :end_time, :place, :limit_number, :detail)';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':title', $gameinfo->title, PDO::PARAM_STR);
        $prepare->bindValue(':game_date', $gameinfo->gameDate, PDO::PARAM_STR);
        $prepare->bindValue(':start_time', $gameinfo->startTime, PDO::PARAM_STR);
        $prepare->bindValue(':end_time', $gameinfo->endTime, PDO::PARAM_STR);
        $prepare->bindValue(':place', $gameinfo->place, PDO::PARAM_STR);
        $prepare->bindValue(':limit_number', $gameinfo->limitNumber, PDO::PARAM_INT);
        $prepare->bindValue(':detail', $gameinfo->detail, PDO::PARAM_STR);
        $prepare->execute();
    }

    public function update(GameInfo $gameinfo) {
        $pdo = DaoFactory::getConnection();
        $sql = 'update game_info set title = :title
        , game_date = :game_date
        , start_time = :start_time
        , end_time = :end_time
        , place = :place
        , limit_number = :limit_number
        , detail = :detail
        where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $gameinfo->id, PDO::PARAM_INT);
        $prepare->bindValue(':title', $gameinfo->title, PDO::PARAM_STR);
        $prepare->bindValue(':game_date', $gameinfo->gameDate, PDO::PARAM_STR);
        $prepare->bindValue(':start_time', $gameinfo->startTime, PDO::PARAM_STR);
        $prepare->bindValue(':end_time', $gameinfo->endTime, PDO::PARAM_STR);
        $prepare->bindValue(':place', $gameinfo->place, PDO::PARAM_STR);
        $prepare->bindValue(':limit_number', $gameinfo->limitNumber, PDO::PARAM_INT);
        $prepare->bindValue(':detail', $gameinfo->detail, PDO::PARAM_STR);
        $prepare->execute();
    }

    public function delete(int $id){
        $pdo = DaoFactory::getConnection();
        // 先に参加者情報を削除しておく
        $detailDao = New DetailDao();
        $detailDao->deleteByGameId($id);
        $sql = "delete from game_info where id = :id";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();

    }
}