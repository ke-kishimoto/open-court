-- イベント
delete from game_info;
insert into game_info (title, short_title, game_date, start_time, end_time, place, limit_number, detail) values 
  ('オープンコート那覇', 'オープン那覇','2020-07-01', '17:00', '19:00', '那覇市民体育館', 25, '詳細')
, ('オープンコート北中', 'オープン北中','2020-07-01', '20:00', '22:00', '北中市民体育館', 30, '詳細')
, ('オープンコート沖縄市', 'オープン沖縄','2020-07-10', '20:00', '22:00', '沖縄市民体育館', 15, '詳細・・・')
, ('オープンコート西原', 'オープン西原','2020-07-15', '20:00', '22:00', '西原体育館', 30, 'バスケする')
, ('ビギナーズ西原', 'オープン西原','2020-07-15', '20:00', '22:00', '西原体育館', 30, '初心者向けのバスケ')
;

-- イベントテンプレ
delete from event_template;
insert into event_template (template_name, title, short_title, place, limit_number, detail) values
('テンプレテスト', 'イベントタイトル', 'ショートタイトル', 'プレイス', 10, 'テンプレ詳細')
, ('オープンコートテンプレ', 'オープンコード', 'オープンコード', '体育館', 20, 'テンプレ詳細')
, ('ビギナーズテンプレ', 'ビギナーズ', 'ビギナーズ', '体育館', 30, '初心者向けのやつ')
;
-- 参加者
delete from participant;
insert into participant (game_id, occupation, sex, name, email, waiting_flg, remark) values 
(1, 1, 1, '坂本', 'aaa@gmail.com', 0, '同伴3名')
, (1, 2, 1, 'bbb', 'bbb@gmail.com', 0, '')
, (1, 1, 2, 'ccc', 'ccc@gmail.com', 0, '')
, (2, 1, 2, 'ccc', 'ccc@gmail.com', 0, '')
, (4, 1, 1, '坂本', 'aaa@gmail.com', 0, '')
, (5, 1, 1, '坂本', 'aaa@gmail.com', 0, '')
;


-- 同伴者
delete from companion;
insert into companion (participant_id, occupation, sex, name) values 
(1, 1, 1, '同伴aaa')
, (1, 1, 2, '同伴bbb')
, (1, 2, 1, '同伴ccc')
, (2, 2, 2, '同伴ddd')
, (2, 1, 2, '同伴eee')
, (3, 3, 2, '同伴eee')
, (3, 3, 1, '同伴eee')
;

-- 設定
delete from config;
insert into config (id, line_token, system_title)values(1, 'SVcGMVbQUmk2xKoiP5PWbSV8tTine4q9BaglYgmB0AY', 'From Schedule');

