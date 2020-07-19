-- create database open_court;

-- イベント情報　
-- テーブル名game_infoじゃなくて、event_infoとかにすればよかったかな.
-- そのうちリファクタリング対象
-- drop view v_participant;
-- drop table game_info; 
create table game_info (
    id serial primary key
    , title varchar(50)
    , short_title varchar(20)
    , game_date date
    , start_time varchar(10)
    , end_time varchar(10)
    , place varchar(100)
    , limit_number int
    , detail varchar(1000)
);

-- テストデータ
insert into game_info (title, game_date, start_time, end_time, place, limit_number, detail) values 
  ('オープンコート那覇', '2020-02-01', '17:00', '19:00', '那覇市民体育館', 25, '詳細')
, ('オープンコート北中', '2020-02-01', '20:00', '22:00', '北中市民体育館', 30, '詳細')
, ('オープンコート沖縄市', '2020-03-01', '20:00', '22:00', '沖縄市民体育館', 15, '詳細・・・')
, ('オープンコート北谷', '2020-03-10', '20:00', '22:00', '体育館', 20, 'わーーー')
, ('オープンコート西原', '2020-03-15', '20:00', '22:00', '西原体育館', 30, 'バスケする')
;

-- イベントのテンプレ
create table event_template (
    id serial primary key
    , template_name varchar(30)
    , title varchar(50)
    , short_title varchar(20)
    , place varchar(100)
    , limit_number int
    , detail varchar(1000)
);

-- テストデータ
insert into event_template (template_name, title, short_title, place, limit_number, detail) values
('テンプレテスト', 'テンプレタイトル', 'ショートタイトル', 'プレイス', 10, 'テンプレ詳細');

-- 参加者
-- drop table participant;
create table participant (
    id serial primary key
    , game_id int
    , occupation int   -- 職種  1：社会、2：大学生、3：高校生
    , sex int -- 性別  1：男、2：女
    , name varchar(50)   -- 参加者名
    , email varchar(50)  -- メール
    , companion int -- 同伴者
    , remark varchar(200)
);

-- テストデータ
insert into participant (game_id, occupation, sex, name, email, remark, companion) values 
(1, 1, 1, 'aaa', 'aaas@gmail.com', '', 0)
, (1, 2, 1, 'bbb', 'aaas@gmail.com', '', 0)
, (1, 1, 2, 'ccc', 'aaas@gmail.com', '同伴2名', 2);

-- 設定
-- 後々はユーザー単位にしたいな
-- drop table config;
create table config(
    id int primary key
    , line_token varchar(200)
);

------------------------------------------------------------------------------------------
--- ビュー
------------------------------------------------------------------------------------------

-- 参加者確認用ビュー
-- drop view v_participant;
create view v_participant as 
(select 
  max(g.id) game_id
, max(game_date) game_date
, max(start_time) start_time
, max(end_time) end_time
, max(place) place
, max(limit_number) limit_number
, count(*) + sum(companion) count
, sum(
    case 
        when occupation = 1 and sex = 1 then 1 + companion -- とりあえず参加者に加えておく
        else 0
    end
) sya_men  -- 社会人男
,  sum(
    case 
        when occupation = 1 and sex = 2 then 1 + companion
        else 0
    end
) sya_women  -- 社会人女
, sum(
    case 
        when occupation = 2 and sex = 1 then 1 + companion
        else 0
    end
) dai_men  -- 大学生男
,  sum(
    case 
        when occupation = 2 and sex = 2 then 1 + companion
        else 0
    end
) dai_women  -- 大学生女
, sum(
    case 
        when occupation = 3 and sex = 1 then 1 + companion
        else 0
    end
) kou_men  -- 高校生男
,  sum(
    case 
        when occupation = 3 and sex = 2 then 1 + companion
        else 0
    end
) kou_women  -- 高校生女
  from game_info g
  inner join participant p
  on g.id = p.game_id
  group by g.id);




SELECT 
g.id 
, max(g.title) 
, max(g.short_title) 
, max(g.game_date) 
, max(g.start_time) 
, max(g.end_time) 
, CASE 
    when max(g.limit_number) <= count(*) + sum(companion) then '満員' 
    else concat('残り', max(g.limit_number) - count(*) - sum(companion), '人') 
  END 
from game_info g 
join participant p 
on g.id = p.game_id 
group by g.id
