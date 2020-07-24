-- イベント
delete from game_info;
insert into game_info (title, short_title, game_date, start_time, end_time, place, limit_number, detail) values 
  ('オープンコート那覇', 'オープン那覇','2020-02-01', '17:00', '19:00', '那覇市民体育館', 25, '詳細')
, ('オープンコート北中', 'オープン北中','2020-02-01', '20:00', '22:00', '北中市民体育館', 30, '詳細')
, ('オープンコート沖縄市', 'オープン沖縄','2020-03-01', '20:00', '22:00', '沖縄市民体育館', 15, '詳細・・・')
, ('オープンコート西原', 'オープン西原','2020-03-15', '20:00', '22:00', '西原体育館', 30, 'バスケする')
, ('ビギナーズ西原', 'オープン西原','2020-03-15', '20:00', '22:00', '西原体育館', 30, '初心者向けのバスケ')
;

-- イベントテンプレ
delete from event_template;
insert into event_template (template_name, title, short_title, place, limit_number, detail) values
('テンプレテスト', 'イベントタイトル', 'ショートタイトル', 'プレイス', 10, 'テンプレ詳細')
, ('テンプレテスト2', 'イベントタイトル2', 'ショートタイトル2', 'プレイス2', 20, 'テンプレ詳細')
, ('テンプレテスト3', 'イベントタイトル3', 'ショートタイトル3', 'プレイス3', 30, 'テンプレ詳細')

-- 参加者
delete from participant;
insert into participant (game_id, occupation, sex, name, email, waiting_flg, remark) values 
(1, 1, 1, 'aaa', 'aaas@gmail.com', 0, '同伴3名')
, (1, 2, 1, 'bbb', 'aaas@gmail.com', 0, '')
, (1, 1, 2, 'ccc', 'aaas@gmail.com', 0, '')
, (2, 1, 2, 'ccc', 'aaas@gmail.com', 0, '')
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
insert into config (id, line_token)values(1, '');

