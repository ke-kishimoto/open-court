<?php
namespace dao;

use dao\DaoFactory;
use PDO;
use entity\GameInfo;

class GameInfoDao 
{

    private $pdo;
    public function __construct() 
    {
        $this->pdo = DaoFactory::getConnection();
    }
    public function getPdo() 
    {
        return $this->pdo;
    }
    public function setPdo(PDO $pdo) 
    {
        $this->pdo = $pdo;
    }

    public function getGameInfo($id) 
    {
        $sql = 'select * from game_info where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id);
        $prepare->execute();

        return $prepare->fetch();
    }

    // 一覧表示用
    public function getGameInfoList($year, $month) 
    {
        $sql = $this->getGameInfoListSQL();
        $sql .= DaoFactory::getGameInfoListSQL();
        $sql .= " and g.delete_flg = 1 ";
        $sql .= " group by g.id order by max(g.game_date)";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':year', $year);
        $prepare->bindValue(':month', $month);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    // カレンダー表示用
    public function getGameInfoListByDate($date) 
    {
        $sql = $this->getGameInfoListSQL();
        $sql .= " where game_date = :date and g.delete_flg = 1 ";
        $sql .= " group by g.id order by max(g.game_date), max(g.start_time)";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':date', $date);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    // 一括予約用
    public function getGameInfoListByAfterDate($date, $email = '') 
    {
        $sql = $this->getGameInfoListSQL();
        $sql .= " where game_date >= :date and g.delete_flg = 1 ";
        if ($email !== '') {
            $sql .= " and not exists(select * from participant where game_id = g.id and email = :email)";
        }
        $sql .= " group by g.id order by max(g.game_date), max(g.start_time)";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':date', $date);
        if ($email !== '') {
            $prepare->bindValue(':email', $email);
        }
        $prepare->execute();

        return $prepare->fetchAll();
    }

    private function getGameInfoListSQL() 
    {
        $sql = "select 
        g.id 
        , max(g.delete_flg) delete_flg
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
                    where waiting_flg = 0
                    and delete_flg = 1) p
        on g.id = p.game_id 
        ";
        return $sql;
    }

    public function insert(GameInfo $gameinfo) 
    {
        $sql = 'insert into game_info (title, short_title, game_date, start_time, end_time, place, limit_number, detail, register_date) 
            values(:title, :short_title, :game_date, :start_time, :end_time, :place, :limit_number, :detail, :register_date)';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':title', $gameinfo->title, PDO::PARAM_STR);
        $prepare->bindValue(':short_title', $gameinfo->shortTitle, PDO::PARAM_STR);
        $prepare->bindValue(':game_date', $gameinfo->gameDate, PDO::PARAM_STR);
        $prepare->bindValue(':start_time', $gameinfo->startTime, PDO::PARAM_STR);
        $prepare->bindValue(':end_time', $gameinfo->endTime, PDO::PARAM_STR);
        $prepare->bindValue(':place', $gameinfo->place, PDO::PARAM_STR);
        $prepare->bindValue(':limit_number', $gameinfo->limitNumber, PDO::PARAM_INT);
        $prepare->bindValue(':detail', $gameinfo->detail, PDO::PARAM_STR);
        $prepare->bindValue(':price1', $gameinfo->price1, PDO::PARAM_STR);
        $prepare->bindValue(':price2', $gameinfo->price2, PDO::PARAM_STR);
        $prepare->bindValue(':price3', $gameinfo->price3, PDO::PARAM_STR);
        $prepare->bindValue(':register_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->execute();
    }

    public function update(GameInfo $gameinfo) 
    {
        $sql = 'update game_info set title = :title
        , short_title = :short_title
        , game_date = :game_date
        , start_time = :start_time
        , end_time = :end_time
        , place = :place
        , limit_number = :limit_number
        , detail = :detail
        , price1 = :price1
        , price2 = :price2
        , price3 = :price3
        , update_date = :update_date
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
        $prepare->bindValue(':price1', $gameinfo->price1, PDO::PARAM_STR);
        $prepare->bindValue(':price2', $gameinfo->price2, PDO::PARAM_STR);
        $prepare->bindValue(':price3', $gameinfo->price3, PDO::PARAM_STR);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->execute();
    }

    public function delete(int $id)
    {
        // 先に参加者情報を削除しておく
        $detailDao = New DetailDao();
        $detailDao->setPdo($this->pdo);
        $detailDao->deleteByGameId($id);
        // $sql = "delete from game_info where id = :id";
        $sql = "update game_info set delete_flg = 9 
        , update_date = :update_date
        where id = :id";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();

    }
}