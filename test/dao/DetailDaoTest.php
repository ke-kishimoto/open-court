<?php
require_once "vendor/autoload.php";
require_once(__DIR__.'/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/DetailDao.php');
require_once('./model/dao/GameInfoDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity/Participant.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use dao\DetailDao;
use entity\Participant;

class ConfigDaoTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @return PHPUnit\Extensions\Database\DB\IDatabaseConnection
     */
    public function getConnection()
    {
        $pdo = new PDO('mysql:dbname=eventman_test;host=127.0.0.1:8889', 'root', 'root');
        return $this->createDefaultDBConnection($pdo, 'eventman_test');
    }

    public function getDataSet()
    {
        return new MyApp_DbUnit_ArrayDataSet(
            [
                'game_info' => [
                    [
                        'id' => 1,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-01',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 25,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                    ],
                    [
                        'id' => 2,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-01',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 2,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                    ],
                    [
                        'id' => 3,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-02',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 4,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                    ],
                    [
                        'id' => 4,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-02',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 5,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                    ],
                ],
                'participant' => [
                    [
                        'id' => 1,
                        'game_id' => 1,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'aaa',
                        'email' => 'aaa@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'aaa',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 2,
                        'game_id' => 1,
                        'occupation' => 2,
                        'sex' => 1,
                        'name' => 'bbb',
                        'email' => 'bbb@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'bbb',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 3,
                        'game_id' => 1,
                        'occupation' => 2,
                        'sex' => 1,
                        'name' => 'ccc',
                        'email' => 'ccc@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'ccc',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 4,
                        'game_id' => 1,
                        'occupation' => 3,
                        'sex' => 1,
                        'name' => 'ddd',
                        'email' => 'ddd@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'ddd',
                        'delete_flg' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 5,
                        'game_id' => 1,
                        'occupation' => 3,
                        'sex' => 1,
                        'name' => 'eee',
                        'email' => 'eee@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'eee',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 6,
                        'game_id' => 1,
                        'occupation' => 3,
                        'sex' => 1,
                        'name' => 'fff',
                        'email' => 'fff@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'fff',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 7,
                        'game_id' => 1,
                        'occupation' => 1,
                        'sex' => 2,
                        'name' => 'ggg',
                        'email' => 'ggg@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'ggg',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 8,
                        'game_id' => 1,
                        'occupation' => 1,
                        'sex' => 2,
                        'name' => 'hhh',
                        'email' => 'hhh@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'hhh',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 9,
                        'game_id' => 1,
                        'occupation' => 1,
                        'sex' => 2,
                        'name' => 'iii',
                        'email' => 'iii@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'iii',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 10,
                        'game_id' => 1,
                        'occupation' => 1,
                        'sex' => 2,
                        'name' => 'jjj',
                        'email' => 'jjj@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'jjj',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 11,
                        'game_id' => 1,
                        'occupation' => 2,
                        'sex' => 2,
                        'name' => 'kkk',
                        'email' => 'kkk@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'kkk',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 12,
                        'game_id' => 1,
                        'occupation' => 2,
                        'sex' => 2,
                        'name' => 'lll',
                        'email' => 'lll@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'lll',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 13,
                        'game_id' => 1,
                        'occupation' => 2,
                        'sex' => 2,
                        'name' => 'mmm',
                        'email' => 'mmm@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'mmm',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 14,
                        'game_id' => 1,
                        'occupation' => 2,
                        'sex' => 2,
                        'name' => 'nnn',
                        'email' => 'nnn@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'nnn',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 15,
                        'game_id' => 1,
                        'occupation' => 2,
                        'sex' => 2,
                        'name' => 'ooo',
                        'email' => 'ooo@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'ooo',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 16,
                        'game_id' => 1,
                        'occupation' => 3,
                        'sex' => 2,
                        'name' => 'ppp',
                        'email' => 'ppp@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'ppp',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 17,
                        'game_id' => 1,
                        'occupation' => 3,
                        'sex' => 2,
                        'name' => 'qqq',
                        'email' => 'qqq@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'qqq',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 18,
                        'game_id' => 1,
                        'occupation' => 3,
                        'sex' => 2,
                        'name' => 'rrr',
                        'email' => 'rrr@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'rrr',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 19,
                        'game_id' => 1,
                        'occupation' => 3,
                        'sex' => 2,
                        'name' => 'sss',
                        'email' => 'sss@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'sss',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 20,
                        'game_id' => 1,
                        'occupation' => 3,
                        'sex' => 2,
                        'name' => 'ttt',
                        'email' => 'ttt@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'ttt',
                        'delete_flg' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 21,
                        'game_id' => 1,
                        'occupation' => 3,
                        'sex' => 2,
                        'name' => 'uuu',
                        'email' => 'uuu@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'uuu',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    // 2
                    [
                        'id' => 22,
                        'game_id' => 2,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'aaa',
                        'email' => 'aaa@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'aaa',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 23,
                        'game_id' => 2,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'bbbb',
                        'email' => 'bbbb@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'bbbb',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 24,
                        'game_id' => 2,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'cccc',
                        'email' => 'cccc@gmail.com',
                        'waiting_flg' => 1,
                        'remark' => 'aaa',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 25,
                        'game_id' => 2,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'dddd',
                        'email' => 'dddd@gmail.com',
                        'waiting_flg' => 1,
                        'remark' => 'aaa',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 26,
                        'game_id' => 2,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'eeee',
                        'email' => 'eeee@gmail.com',
                        'waiting_flg' => 1,
                        'remark' => 'aaa',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 27,
                        'game_id' => 3,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => '1111',
                        'email' => '1111@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'aaa',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 28,
                        'game_id' => 3,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => '2222',
                        'email' => '2222@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'aaa',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                    [
                        'id' => 29,
                        'game_id' => 4,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => '2222',
                        'email' => '2222@gmail.com',
                        'waiting_flg' => 0,
                        'remark' => 'aaa',
                        'delete_flg' => 1,
                        'attendance' => 1,
                        'amount' => 100,
                        'tel' => '000-1111-2222',
                    ],
                ],
                'companion' => [
                    [
                        'id' => 1,
                        'participant_id' => 29,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'douhan'
                    ]
                ]
            ]
        );
    }

    public function testInsert()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $participant = new Participant();
        $participant->gameId = 1;
        $participant->occupation = 1;
        $participant->sex = 1;
        $participant->name = '追加';
        $participant->email = 'add@gmail.com';
        $participant->waitingFlg = 0;
        $participant->remark = '備考';
        $participant->amount = 150;
        $participant->tel = '090-1234-5678';

        $dao->insert($participant);

        $list = $dao->getParticipantList(1);
        $this->assertSame(22, count($list));

        $participant = $dao->getParticipant(30);
        $this->assertSame('30', $participant['id']);
        $this->assertSame('1', $participant['game_id']);
        $this->assertSame('1', $participant['occupation']);
        $this->assertSame('1', $participant['sex']);
        $this->assertSame('追加', $participant['name']);
        $this->assertSame('add@gmail.com', $participant['email']);
        $this->assertSame('0', $participant['waiting_flg']);
        $this->assertSame('備考', $participant['remark']);
        $this->assertSame('1', $participant['attendance']);
        $this->assertSame('150', $participant['amount']);
        $this->assertSame('社会人', $participant['occupation_name']);
        $this->assertSame('男性', $participant['sex_name']);
        $this->assertSame('090-1234-5678', $participant['tel']);
    }

    public function testUpdate()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $participant = new Participant();
        $participant->id = 1;
        $participant->occupation = 2;
        $participant->sex = 2;
        $participant->name = '更新';
        $participant->email = 'update@gmail.com';
        $participant->waitingFlg = 0;
        $participant->remark = '更新';
        $participant->amount = 200;
        $participant->tel = '090-2345-6789';

        $dao->update($participant);

        $participant = $dao->getParticipant(1);
        $this->assertSame('1', $participant['id']);
        $this->assertSame('2', $participant['occupation']);
        $this->assertSame('2', $participant['sex']);
        $this->assertSame('更新', $participant['name']);
        $this->assertSame('update@gmail.com', $participant['email']);
        $this->assertSame('0', $participant['waiting_flg']);
        $this->assertSame('更新', $participant['remark']);
        $this->assertSame('200', $participant['amount']);
        $this->assertSame('大学・専門学校', $participant['occupation_name']);
        $this->assertSame('女性', $participant['sex_name']);
        $this->assertSame('090-2345-6789', $participant['tel']);
    }

    public function testDelete()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $dao->delete(1);
        $list = $dao->getParticipantList(1);

        $this->assertSame(20, count($list));
        

    }

    public function testGetParticipant()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $participant = $dao->getParticipant(1);
        $this->assertSame('1', $participant['id']);
        $this->assertSame('1', $participant['game_id']);
        $this->assertSame('1', $participant['occupation']);
        $this->assertSame('1', $participant['sex']);
        $this->assertSame('aaa', $participant['name']);
        $this->assertSame('aaa@gmail.com', $participant['email']);
        $this->assertSame('0', $participant['waiting_flg']);
        $this->assertSame('aaa', $participant['remark']);
        $this->assertSame('社会人', $participant['occupation_name']);
        $this->assertSame('男性', $participant['sex_name']);
        $this->assertSame('000-1111-2222', $participant['tel']);
    }

    public function testGetParticipantList()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        // 全員
        $list = $dao->getParticipantList(1);
        $this->assertSame(21, count($list));

        // 社会人男性
        $list = $dao->getParticipantList(1, 1, 1);
        $this->assertSame(1, count($list));
        $this->assertSame($list[0]['chk'], '重複あり');

        // 大学生男性
        $list = $dao->getParticipantList(1, 2, 1);
        $this->assertSame(2, count($list));

        // 高校生男性
        $list = $dao->getParticipantList(1, 3, 1);
        $this->assertSame(3, count($list));

        // 社会人女性
        $list = $dao->getParticipantList(1, 1, 2);
        $this->assertSame(4, count($list));

        // 大学生女性
        $list = $dao->getParticipantList(1, 2, 2);
        $this->assertSame(5, count($list));

        // 高校生女性
        $list = $dao->getParticipantList(1, 3, 2);
        $this->assertSame(6, count($list));

        // キャンセル待ちじゃない人
        $list = $dao->getParticipantList(2, 0, 0, 0);
        $this->assertSame(2, count($list));

        // キャンセル待ちの人
        $list = $dao->getParticipantList(2, 0, 0, 1);
        $this->assertSame(3, count($list));

        // 同伴あり
        $list = $dao->getParticipantList(4);
        $this->assertSame(2, count($list));
    }

    public function testGetDetail()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $detail = $dao->getDetail(1);
        $this->assertSame('21', $detail['cnt']);
        $this->assertSame('5', $detail['sya_all']);
        $this->assertSame('1', $detail['sya_men']);
        $this->assertSame('4', $detail['sya_women']);
        $this->assertSame('7', $detail['dai_all']);
        $this->assertSame('2', $detail['dai_men']);
        $this->assertSame('5', $detail['dai_women']);
        $this->assertSame('9', $detail['kou_all']);
        $this->assertSame('3', $detail['kou_men']);
        $this->assertSame('6', $detail['kou_women']);

        $detail = $dao->getDetail(2);
        $this->assertSame('2', $detail['cnt']);
        $this->assertSame('3', $detail['waiting_cnt']);

        $detail = $dao->getDetail(4);
        $this->assertSame('2', $detail['cnt']);
        $this->assertSame('2', $detail['sya_all']);
        $this->assertSame('2', $detail['sya_men']);
       
    }

    public function testDeleteByGameId()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $dao->deleteByGameId(1);
        $list = $dao->getParticipantList(1);
        $this->assertSame(0, count($list));
    }

    public function testLimitCheck()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $this->assertSame(false, $dao->limitCheck(3, 2));
        $this->assertSame(true, $dao->limitCheck(3, 3));
    }

    public function testGetParticipantId()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $participant = new Participant();
        $participant->gameId = 1;
        $participant->email = 'aaa@gmail.com';
        $this->assertSame('1', $dao->getParticipantId($participant));
    }

    public function testUpdateWaitingFlg()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $dao->updateWaitingFlg(1);
        $participant = $dao->getParticipant(1);
        $this->assertSame('1', $participant['waiting_flg']);

        $dao->updateWaitingFlg(1);
        $participant = $dao->getParticipant(1);
        $this->assertSame('0', $participant['waiting_flg']);
    }

    public function testExistsCheck()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $this->assertSame(true, $dao->existsCheck(1, 'aaa@gmail.com'));
        $this->assertSame(false, $dao->existsCheck(1, 'abc@gmail.com'));
    }

    public function testDeleteByMailAddress()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $this->assertSame(1, $dao->deleteByMailAddress(1, 'aaa@gmail.com'));
    }

    public function testGetEventListByEmail()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $list = $dao->getEventListByEmail('aaa@gmail.com', '2020-01-01');
        $this->assertSame(2, count($list));

    }

    public function testGetWitingList()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $gameId = 2;
        $list = $dao->getWitingList($gameId);
        $this->assertSame(3, count($list));
    }

    public function testUpdateAttendance()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DetailDao();

        $id = 1;
        $participant = $dao->updateAttendance($id);
        $this->assertSame('2', $participant['attendance']);

    }

  
}