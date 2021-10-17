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

    public function getYearMonthSales($year)
    {
        $sql = "select date_format(game_date, '%m') month, sum(participantnum) cnt, sum(amount) amount
        from game_info
        where date_format(game_date, '%Y') = :year
        and delete_flg = 1
        group by date_format(game_date, '%m')
        order by date_format(game_date, '%m')";
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':year', $year, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function getYearSales()
    {
        $sql = "select date_format(game_date, '%Y') date, sum(participantnum) cnt, sum(amount) amount
        from game_info
        where delete_flg = 1
        group by date_format(game_date, '%Y')
        order by date_format(game_date, '%Y')";
        // $sql = "select 
        // date
        // ,count(id) as cnt
        // ,sum(amount) as amount
        // from(
        //     select 
        //     p.id
        //     , p.amount
        //     , date_format(g.game_date, '%Y') as date
        //     from participant p
        //     inner join game_info g
        //     on p.game_id = g.id
        //     where  p.delete_flg = 1
        //     and p.attendance = 1
        //     union all
        //     select  
        //     participant_id
        //     , c.amount
        //     , date_format(g.game_date, '%Y') as date
        //     from companion c
        //     inner join participant p on  c.participant_id = p.id
        //     inner join game_info g on g.id = p.game_id
        //     where participant_id in (select id from participant where delete_flg = 1)
        //     and c.delete_flg = 1
        //     and p.attendance = 1
        //  ) as tmp
        // group by date
        // order by date";
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    public function getMonthSales($year, $month)
    {
        $sql = "select 
        p.game_id game_id
        , max(g.game_date) date
        , max(title) title
        , coalesce(participantnum, count(*) + (select count(*) from companion where participant_id in (select id from participant where game_id = g.id and delete_flg = 1) and delete_flg = 1 and attendance = 1 ), 0) cnt
        , coalesce(g.amount, sum(p.amount) + (select coalesce(sum(amount), 0) from companion where participant_id in (select id from participant where game_id = g.id and delete_flg = 1) and delete_flg = 1 and attendance = 1)) amount
        , expenses
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
    when attendance = 1 then '○'
    else '×'
    end attendance_name
, p.amount
, amount_remark
from participant p
inner join game_info g
on p.game_id = g.id
where p.game_id = :game_id
and p.delete_flg = 1
union all
select  
participant_id 
, c.name 
, g.title
, c.attendance
, case
    when c.attendance = 1 then '○'
    else '×'
    end attendance_name
, c.amount
, c.amount_remark
from companion c
inner join participant p on  c.participant_id = p.id
inner join game_info g on g.id = p.game_id
where participant_id in (select id from participant where game_id = :game_id and delete_flg = 1)
and c.delete_flg = 1
order by id";
        $prepare = $this->getPdo()->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();

    }
}