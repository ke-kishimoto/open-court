------------------------------------------------------------------------------------------
--- 確認用SQL
------------------------------------------------------------------------------------------

-- 参加者集計用
select 
count(*) cnt
, sum(
    case 
        when occupation = 1 and sex = 1 then 1
        else 0
    end
) sya_men  -- 社会人男
,  sum(
    case 
        when occupation = 1 and sex = 2 then 1
        else 0
    end
) sya_women  -- 社会人女
, sum(
    case 
        when occupation = 2 and sex = 1 then 1
        else 0
    end
) dai_men  -- 大学生男
,  sum(
    case 
        when occupation = 2 and sex = 2 then 1
        else 0
    end
) dai_women  -- 大学生女
, sum(
    case 
        when occupation = 3 and sex = 1 then 1
        else 0
    end
) kou_men  -- 高校生男
,  sum(
    case 
        when occupation = 3 and sex = 2 then 1
        else 0
    end
) kou_women  -- 高校生女
from 
(select occupation, sex 
from participant
where game_id = :game_id
and waiting_flg = :waiting_flg
union all
select occupation, sex
from companion
where participant_id in (select id from participant where game_id = :game_id and waiting_flg = :waiting_flg)
) p

-- 参加者一覧
select 
id
, main
, name
, case 
    when occupation =  1 then '社会人'
    when occupation =  2 then '大学・専門学校'
    when occupation =  3 then '高校'
    else 'その他' 
  end occupation_name
, case
    when sex = 1 then '男性'
    when sex = 2 then '女性'
  end sex_name 
, waiting_flg
, case
    when waiting_flg = 1 then 'キャンセル待ち' 
    else ''
  end waiting_name
, enail
, remark
from 
(
select id, 1 main ,name, occupation, sex, waiting_flg, remark
from participant
where game_id = :game_id
union all
select participant_id, 0 ,name, occupation, sex, 0, ''
from companion
where participant_id in (select id from participant where game_id = :game_id)
) p
order by id, main;


-- イベント参加人数が定員に達しているかを確認
select 
g.id 
, max(g.title) title
, max(g.short_title) short_title
, max(g.game_date) game_date
, max(g.start_time) start_time
, max(g.end_time) end_time
, max(g.limit_number) limit_number
, count(p.id) + coalesce(sum(cnt), 0) participants_number
, case 
    when max(g.limit_number) <= coalesce(count(*), 0) + coalesce(sum(cnt), 0) then '定員に達しました' 
    else concat('残り', max(g.limit_number) - coalesce(count(p.id), 0) - coalesce(sum(cnt), 0), '人') 
  end current_status
from game_info g 
left join (select *
            , (select count(*) from companion where participant_id = participant.id) cnt
            from participant) p
on g.id = p.game_id 
group by g.id;

