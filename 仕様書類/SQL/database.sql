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
    , price1 int
    , price2 int
    , price3 int
    , delete_flg int default 1
    , register_date timestamp null default null
    , update_date timestamp null default null
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
    , price1 int
    , price2 int
    , price3 int
    , delete_flg int default 1
    , register_date timestamp null default null
    , update_date timestamp null default null
);

-- 参加者
-- drop table participant;
create table participant (
    id serial primary key
    , game_id int
    , occupation int     -- 職種  1：社会、2：大学生、3：高校生
    , sex int            -- 性別  1：男、2：女
    , name varchar(50)   -- 参加者名
    , email varchar(50)  -- メール
    , waiting_flg int    -- キャンセル待ちフラグ 0：通常、1：キャンセル待ち
    , remark varchar(200)  -- 備考
    , attendance int default 1 -- 出欠  -- 1：出席、２：出席
    , amount int           -- 回収金額
    , amount_remark varchar(200) -- 売上備考
    , tel varchar(13)      -- 電話番号
    , delete_flg int default 1
    , register_date timestamp null default null   -- 登録日時
    , update_date timestamp null default null     -- 更新日時
);
-- インデックス
create index participant_idx_game on participant (game_id); 

-- 出欠と回収金額
alter table participant add column attendance int default 1; 
alter table participant add column amount int;
alter table participant add column amount_remark varchar(200);
-- 電話番号
alter table participant add column tel varchar(13);


-- 同伴者
-- drop table companion;
create table companion (
    id serial primary key
    , participant_id int -- 参加者id
    , occupation int     -- 職種  1：社会、2：大学生、3：高校生
    , sex int            -- 性別  1：男、2：女
    , name varchar(50)   -- 参加者名
    , attendance int default 1 -- 1：出席、2：欠席
    , amount int         -- 回収金額
    , amount_remark varchar(200) -- 売上備考
    , delete_flg int default 1
    , register_date timestamp null default null
    , update_date timestamp null default null
);
-- インデックス
create index companion_idx_participant on companion (participant_id);

-- 出欠と回収金額
alter table companion add column attendance int default 1; 
alter table companion add column amount int;
alter table companion add column amount_remark varchar(200);

-- 設定
-- 後々はユーザー単位にしたいな
-- drop table config;
create table config(
    id serial primary key
    , line_token varchar(200)
    , system_title varchar(30)
    , bg_color varchar(30)  -- 背景色、何個か選べるようにする
    , logo_img_path varchar(200)
    , waiting_flg_auto_update int  -- キャンセル待ち自動更新区分 0：手動、1：自動
    , delete_flg int default 1
    , register_date timestamp null default null
    , update_date timestamp null default null
);

alter table config add column delete_flg int default 1;

-- ユーザー
-- drop table users;
create table users(
  id serial primary key
  , admin_flg int  -- 0:一般利用者、1:管理者、2:スーパーユーザー
  , email varchar(50) unique
  , name varchar(50)
  , password varchar(200) -- ハッシュ化して保存
  , occupation int        -- 職種  1：社会、2：大学生、3：高校生
  , sex int               -- 性別  1：男、2：女
  , remark varchar(200)   -- 備考
  , register_date timestamp null default null   -- 登録日時
  , update_date timestamp null default null     -- 更新日時
  , delete_flg int default 1
  , tel varchar(13)
);
-- 電話番号
alter table users add column tel varchar(13);


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

-- 問い合わせ
-- drop table inquiry; 
create table inquiry(
    id serial primary key
    , game_id int
    , name varchar(50)
    , email varchar(50)
    , content varchar(2000)
    , status_flg int  -- 0：未完了、1：完了済み
    , register_date timestamp null default null
    , update_date timestamp null default null
    , delete_flg int default 1
);

-- 削除フラグ追加
alter table inquiry add column delete_flg int default 1;

-- お知らせ
-- drop table notice;
create table notice(
    id serial primary key
    , title varchar(30)
    , content varchar(2000)
    , register_date timestamp null default null
    , update_date timestamp null default null
    , delete_flg int default 1
);


-- 障害報告
-- drop table trouble_report;
create table trouble_report(
    id serial primary key
    , name varchar(50)
    , category int -- 1：障害、２：要望、３、その他
    , title varchar(30)
    , content varchar(2000)
    , delete_flg int default 1 -- 9：削除済み
    , status_flg int default 0 -- 0：未完了、1：完了済み
    , register_date timestamp null default null
    , update_date timestamp null default null
);

