-- タイムスタンプ用のカラム追加

alter table game_info drop column register_date;
alter table game_info drop column update_date;
alter table game_info add column register_date timestamp null default null;
alter table game_info add column update_date timestamp null default null;

alter table event_template drop column register_date;
alter table event_template drop column update_date;
alter table event_template add column register_date timestamp null default null;
alter table event_template add column update_date timestamp null default null;


alter table participant drop column register_date;
alter table participant drop column update_date;
alter table participant add column register_date timestamp null default null;
alter table participant add column update_date timestamp null default null;


alter table companion drop column register_date;
alter table companion drop column update_date;
alter table companion add column register_date timestamp null default null;
alter table companion add column update_date timestamp null default null;


alter table config drop column register_date;
alter table config drop column update_date;
alter table config add column register_date timestamp null default null;
alter table config add column update_date timestamp null default null;


alter table users drop column register_date;
alter table users drop column update_date;
alter table users add column register_date timestamp null default null;
alter table users add column update_date timestamp null default null;

alter table default_companion drop column register_date;
alter table default_companion drop column update_date;
alter table default_companion add column register_date timestamp null default null;
alter table default_companion add column update_date timestamp null default null;

alter table inquiry drop column register_date;
alter table inquiry drop column update_date;
alter table inquiry add column register_date timestamp null default null;
alter table inquiry add column update_date timestamp null default null;