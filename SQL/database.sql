-- create database open_court;

-- MAMP
-- /Applications/MAMP/Library/bin/
-- ./mysql -u root -p

-- イベント情報　
-- テーブル名game_infoじゃなくて、event_infoとかにすればよかったかな.
-- そのうちリファクタリング対象
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

-- イベントのテンプレ
-- drop table event_template; 
create table event_template (
    id serial primary key
    , template_name varchar(30)
    , title varchar(50)
    , short_title varchar(20)
    , place varchar(100)
    , limit_number int
    , detail varchar(1000)
);

-- 参加者
-- drop table participant;
create table participant (
    id serial primary key
    , game_id int
    , occupation int   -- 職種  1：社会、2：大学生、3：高校生
    , sex int -- 性別  1：男、2：女
    , name varchar(50)   -- 参加者名
    , email varchar(50)  -- メール
    , waiting_flg int -- キャンセル待ちフラグ 0：通常、1：キャンセル待ち
    , remark varchar(200)
);
-- インデックス
create index participant_idx_game on participant (game_id); 

-- 同伴者
-- drop table companion;
create table companion (
    id serial primary key
    , participant_id int -- 参加者id
    , occupation int   -- 職種  1：社会、2：大学生、3：高校生
    , sex int -- 性別  1：男、2：女
    , name varchar(50)   -- 参加者名
);
-- インデックス
create index companion_idx_participant on companion (participant_id);

-- 設定
-- 後々はユーザー単位にしたいな
-- drop table config;
create table config(
    id int primary key
    , line_token varchar(200)
    , system_title varchar(30)
);
