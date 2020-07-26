<?php
namespace dao;

require_once(dirname(__FILE__).'/DaoFactory.php');
require_once(dirname(__FILE__).'/DetailDao.php');

use dao\DaoFactory;
use PDO;
use entity\GameInfo;

class GameInfoDao {

    private $pdo;
    public function __construct() {
        $this->pdo = DaoFactory::getConnection();
    }
    public function getPdo() {
        return $this->pdo;
    }
    public function setPdo(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getGameInfoId($date) {
        $sql = 'select id from game_info where game_date = :gameDate';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':gameDate', $date);
        $prepare->execute();

        $result = $prepare->fetch();
        return $result['id'];
    }

    public function getGameInfo($id) {
        $sql = 'select * from game_info where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id);
        $prepare->execute();

        return $prepare->fetch();
    }

    public function getGameInfoList($year, $month) {
        $sql = "select 
        g.id 
        , max(g.title) title
        , max(g.short_title) short_title
        , max(g.game_date) game_date
        , max(g.start_time) start_time
        , max(g.end_time) end_time
        , max(g.place) place
        , max(g.limit_number) limit_number
        , count(p.id) + coalesce(sum(cnt), 0) participants_number
        , case 
            when max(g.limit_number) <= coalesce(count(*), 0) + coalesce(sum(cnt), 0) then '定員に達しました' 
            else concat('残り', max(g.limit_number) - coalesce(count(p.id), 0) - coalesce(sum(cnt), 0), '人') 
          end current_status
        , case 
            when max(g.limit_number) <= (coalesce(count(*), 0) + coalesce(sum(cnt), 0)) then '✖️'
            when ceil(max(g.limit_number) / 4) > (max(g.limit_number) - coalesce(count(*), 0) - coalesce(sum(cnt), 0)) then '△'
            else '○'
          end mark
        from game_info g 
        left join (select *
                    , (select count(*) from companion where participant_id = participant.id) cnt
                    from participant
                    where waiting_flg = 0) p
        on g.id = p.game_id 
        ";
        $sql .= DaoFactory::getGameInfoListSQL();
        $sql .= "group by g.id order by max(g.game_date)";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':year', $year);
        $prepare->bindValue(':month', $month);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    public function getGameInfoListByDate($date) {
        $sql = "select * from game_info where game_date = :date";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':date', $date);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    public function insert(GameInfo $gameinfo) {
        $sql = 'insert into game_info (title, short_title, game_date, start_time, end_time, place, limit_number, detail) 
            values(:title, :short_title, :game_date, :start_time, :end_time, :place, :limit_number, :detail)';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':title', $gameinfo->title, PDO::PARAM_STR);
        $prepare->bindValue(':short_title', $gameinfo->shortTitle, PDO::PARAM_STR);
        $prepare->bindValue(':game_date', $gameinfo->gameDate, PDO::PARAM_STR);
        $prepare->bindValue(':start_time', $gameinfo->startTime, PDO::PARAM_STR);
        $prepare->bindValue(':end_time', $gameinfo->endTime, PDO::PARAM_STR);
        $prepare->bindValue(':place', $gameinfo->place, PDO::PARAM_STR);
        $prepare->bindValue(':limit_number', $gameinfo->limitNumber, PDO::PARAM_INT);
        $prepare->bindValue(':detail', $gameinfo->detail, PDO::PARAM_STR);
        $prepare->execute();
    }

    public function update(GameInfo $gameinfo) {
        $sql = 'update game_info set title = :title
        , short_title = :short_title
        , game_date = :game_date
        , start_time = :start_time
        , end_time = :end_time
        , place = :place
        , limit_number = :limit_number
        , detail = :detail
        where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $gameinfo->id, PDO::PARAM_INT);
        $prepare->bindValue(':title', $gameinfo->title, PDO::PARAM_STR);
        $prepare->bindValue(':short_title', $gameinfo->shortTitle, PDO::PARAM_STR);
        $prepare->bindValue(':game_date', $gameinfo->gameDate, PDO::PARAM_STR);
        $prepare->bindValue(':start_time', $gameinfo->startTime, PDO::PARAM_STR);
        $prepare->bindValue(':end_time', $gameinfo->endTime, PDO::PARAM_STR);
        $prepare->bindValue(':place', $gameinfo->place, PDO::PARAM_STR);
        $prepare->bindValue(':limit_number', $gameinfo->limitNumber, PDO::PARAM_INT);
        $prepare->bindValue(':detail', $gameinfo->detail, PDO::PARAM_STR);
        $prepare->execute();
    }

    public function delete(int $id){
        // 先に参加者情報を削除しておく
        $detailDao = New DetailDao();
        $detailDao->setPdo($this->pdo);
        $detailDao->deleteByGameId($id);
        $sql = "delete from game_info where id = :id";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();

    }
}