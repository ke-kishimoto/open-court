<?php

namespace dao;
use PDO;
class SalesDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'participant';
    }

    public function getMonthSales($year, $month)
    {
        $sql = "select 
        p.game_id game_id
        , max(g.game_date) date
        , max(title) title
        , count(*) + (select count(*) from companion where participant_id = p.id and delete_flg = 1 and attendance = 1 ) cnt
        , sum(amount) + (select sum(amount) from companion where participant_id = p.id and delete_flg = 1 and attendance = 1 ) amount
        from participant p
        inner join game_info g
        on p.game_id = g.id
        where g.delete_flg = 1
        and p.delete_flg = 1
        and p.attendance = 1
        and g.game_date between :start_date and :last_date
        group by p.game_id
        order by p.game_id";
        $prepare = $this->getPdo()->prepare($sql);
        $first_date = date('Y-m-d', strtotime('first day of ' . "{$year}-{$month}"));
        $last_date = date('Y-m-d', strtotime('last day of ' . "{$year}-{$month}"));
        $prepare->bindValue(':start_date', $first_date, PDO::PARAM_STR);
        $prepare->bindValue(':last_date', $last_date, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function getSalesDetail(int $gameId)
    {
        $sql = "select 
        p.id
        , name
        , title
        , attendance
        , case
            when attendance = 1 then '出席'
            else '欠席'
          end attendance_name
        , amount
        , amount_remark
        from participant p
        inner join game_info g
        on p.game_id = g.id
        where p.game_id = :game_id
        and p.delete_flg = 1
        order by p.id";
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();

    }
}