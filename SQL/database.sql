-- DB名は任意。そのうち変えたい
-- create database open_court;

-- MAMP用接続
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
    , register_date timestamp null default null
    , update_date timestamp null default null
    , delete_flg int default 1
);

-- 追加用
-- alter table game_info add column register_date timestamp null default null;
-- alter table game_info add column update_date timestamp null default null;
-- alter table game_info add column delete_flg int default 1;

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
    , register_date timestamp null default null
    , update_date timestamp null default null
    , delete_flg int default 1
);

-- 追加用
-- alter table event_template add column register_date timestamp null default null;
-- alter table event_template add column update_date timestamp null default null;
-- alter table event_template add column delete_flg int default 1;

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
    , remark varchar(200)  -- 備考
    , register_date timestamp null default null   -- 登録日時
    , update_date timestamp null default null     -- 更新日時
    , delete_flg int default 1
);
-- インデックス
create index participant_idx_game on participant (game_id); 

-- 追加用
-- alter table participant add column register_date timestamp null default null;
-- alter table participant add column update_date timestamp null default null;
-- alter table participant add column delete_flg int default 1;

-- 同伴者
-- drop table companion;
create table companion (
    id serial primary key
    , participant_id int -- 参加者id
    , occupation int   -- 職種  1：社会、2：大学生、3：高校生
    , sex int -- 性別  1：男、2：女
    , name varchar(50)   -- 参加者名
    , register_date timestamp null default null
    , update_date timestamp null default null
    , delete_flg int default 1
);
-- インデックス
create index companion_idx_participant on companion (participant_id);

-- 追加用
-- alter table companion add column register_date timestamp null default null;
-- alter table companion add column update_date timestamp null default null;
-- alter table companion add column delete_flg int default 1;

-- 設定
-- 後々はユーザー単位にしたいな
-- drop table config;
create table config(
    id int primary key
    , line_token varchar(200)
    , system_title varchar(30)
    , bg_color varchar(30)  -- 背景色、何個か選べるようにする
    , logo_img_path varchar(200)
    , register_date timestamp null default null
    , update_date timestamp null default null
    , waiting_flg_auto_update int
);

-- 追加用
-- alter table config add column bg_color varchar(30);
-- alter table config add column logo_img_path varchar(200);
-- alter table config add column register_date timestamp null default null;
-- alter table config add column update_date timestamp null default null;
-- alter table config add column waiting_flg_auto_update int;

-- ユーザー
-- drop table users;
create table users(
  id serial primary key
  , admin_flg int  -- 0:一般利用者、1:管理者　使う分からんけど
  , email varchar(50) unique
  , name varchar(50)
  , password varchar(200) -- ハッシュ化して保存
  , occupation int   -- 職種  1：社会、2：大学生、3：高校生
  , sex int -- 性別  1：男、2：女
  , remark varchar(200)  -- 備考
  , register_date timestamp null default null   -- 登録日時
  , update_date timestamp null default null     -- 更新日時
  , delete_flg int default 1
);

-- 追加用
-- alter table users drop column register_date;
-- alter table users drop column update_date;
-- alter table users add column register_date timestamp null default null;
-- alter table users add column update_date timestamp null default null;

-- alter table users add column delete_flg int default 1;

-- ユーザーに付随する同伴者の初期値
-- drop table default_companion;
create table default_companion(
    id serial primary key 
    , user_id int -- ユーザーid
    , occupation int   -- 職種  1：社会、2：大学生、3：高校生
    , sex int -- 性別  1：男、2：女
    , name varchar(50)   -- 参加者名
    , register_date timestamp null default null
    , update_date timestamp null default null
    , delete_flg int default 1
);

-- 追加用
-- alter table default_companion add column register_date timestamp null default null;
-- alter table default_companion add column update_date timestamp null default null;
-- alter table default_companion add column delete_flg int default 1;

-- 問い合わせ
-- drop table inquiry; 
create table inquiry(
    id serial primary key
    , game_id int
    , name varchar(50)
    , email varchar(50)
    , content varchar(2000)
    , status_flg int
    , register_date timestamp null default null
    , update_date timestamp null default null
);

-- alter table inquiry modify column register_date timestamp null default null;
-- alter table inquiry modify column update_date timestamp null default null;

-- postgresql用
-- alter table inquiry alter column register_date type timestamp;
-- alter table inquiry alter column update_date type timestamp;

