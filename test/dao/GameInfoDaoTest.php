<?php
require_once "vendor/autoload.php";
require_once(__DIR__.'/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/BaseDao.php');
require_once('./model/dao/DetailDao.php');
require_once('./model/dao/GameInfoDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity/BaseEntity.php');
require_once('./model/entity/GameInfo.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use dao\GameInfoDao;
use entity\GameInfo;
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
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 2,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-01',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 3,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-02',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 4,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-02',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 5,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-02',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 6,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-03',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 7,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-03',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 8,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-03',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 9,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-01-03',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 10,
                        'title' => 'イベントタイトル',
                        'short_title' => 'イベント略称',
                        'game_date' => '2020-02-01',
                        'start_time' => '18:00',
                        'end_time' => '20:00',
                        'place' => '那覇',
                        'limit_number' => 20,
                        'detail' => 'イベントです。',
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    
                ],
                'participant' => [
                    [
                        'id' => 1,
                        'game_id' => 5,
                        'email' => 'kishimoto@gmail.com'
                    ]
                ]
            ]
        );
    }

    public function testGetGameInfo()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new GameInfoDao();

        $gameInfo = $dao->selectById(1);
        $this->assertSame('1', $gameInfo['id']);
        $this->assertSame('イベントタイトル', $gameInfo['title']);
        $this->assertSame('イベント略称', $gameInfo['short_title']);
        $this->assertSame('2020-01-01', $gameInfo['game_date']);
        $this->assertSame('18:00', $gameInfo['start_time']);
        $this->assertSame('20:00', $gameInfo['end_time']);
        $this->assertSame('那覇', $gameInfo['place']);
        $this->assertSame('20', $gameInfo['limit_number']);
        $this->assertSame('イベントです。', $gameInfo['detail']);
        $this->assertSame('1', $gameInfo['delete_flg']);
        $this->assertSame('100', $gameInfo['price1']);
        $this->assertSame('200', $gameInfo['price2']);
        $this->assertSame('300', $gameInfo['price3']);
    }

    public function testGetGameInfoList()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new GameInfoDao();

        $list = $dao->getGameInfoList('2020', '01');
        $this->assertSame(9, count($list));
    }

    public function testGetGameInfoListByAfterDate()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new GameInfoDao();

        $list = $dao->getGameInfoListByAfterDate('2020-01-02');
        $this->assertSame(8, count($list));

        $list = $dao->getGameInfoListByAfterDate('2020-01-02', 'kishimoto@gmail.com');
        $this->assertSame(7, count($list));
    }

    public function testInsert()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new GameInfoDao();

        $gameInfo = new GameInfo();
        $gameInfo->title = '追加イベント';
        $gameInfo->shortTitle = '追加ショート';
        $gameInfo->gameDate = '2020-02-02';
        $gameInfo->startTime = '18:00';
        $gameInfo->endTime = '20:00';
        $gameInfo->place = '追加場所';
        $gameInfo->limitNumber = 35;
        $gameInfo->detail = '追加詳細';
        $gameInfo->price1 = 100;
        $gameInfo->price2 = 200;
        $gameInfo->price3 = 300;

        $dao->insert($gameInfo);

        $gameInfo = $dao->selectById(11);
        $this->assertSame('11', $gameInfo['id']);
        $this->assertSame('追加イベント', $gameInfo['title']);
        $this->assertSame('追加ショート', $gameInfo['short_title']);
        $this->assertSame('2020-02-02', $gameInfo['game_date']);
        $this->assertSame('18:00', $gameInfo['start_time']);
        $this->assertSame('20:00', $gameInfo['end_time']);
        $this->assertSame('追加場所', $gameInfo['place']);
        $this->assertSame('35', $gameInfo['limit_number']);
        $this->assertSame('追加詳細', $gameInfo['detail']);
        $this->assertSame('1', $gameInfo['delete_flg']);
        $this->assertSame('100', $gameInfo['price1']);
        $this->assertSame('200', $gameInfo['price2']);
        $this->assertSame('300', $gameInfo['price3']);

    }

    public function testUpdate()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new GameInfoDao();

        $gameInfo = new GameInfo();
        $gameInfo->id = 1;
        $gameInfo->delete_flg = 9;
        $gameInfo->title = '更新イベント';
        $gameInfo->shortTitle = '更新ショート';
        $gameInfo->gameDate = '2020-02-02';
        $gameInfo->startTime = '18:00';
        $gameInfo->endTime = '20:00';
        $gameInfo->place = '更新場所';
        $gameInfo->limitNumber = 35;
        $gameInfo->detail = '更新詳細';
        $gameInfo->price1 = 1000;
        $gameInfo->price2 = 2000;
        $gameInfo->price3 = 3000;

        $dao->update($gameInfo);

        $gameInfo = $dao->selectById(1);
        $this->assertSame('1', $gameInfo['id']);
        $this->assertSame('更新イベント', $gameInfo['title']);
        $this->assertSame('更新ショート', $gameInfo['short_title']);
        $this->assertSame('2020-02-02', $gameInfo['game_date']);
        $this->assertSame('18:00', $gameInfo['start_time']);
        $this->assertSame('20:00', $gameInfo['end_time']);
        $this->assertSame('更新場所', $gameInfo['place']);
        $this->assertSame('35', $gameInfo['limit_number']);
        $this->assertSame('更新詳細', $gameInfo['detail']);
        $this->assertSame('9', $gameInfo['delete_flg']);
        $this->assertSame('1000', $gameInfo['price1']);
        $this->assertSame('2000', $gameInfo['price2']);
        $this->assertSame('3000', $gameInfo['price3']);

    }

    public function testDelete()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new GameInfoDao();

        $dao->delete(1);

        $list = $dao->getGameInfoList('2020', '01');
        $this->assertSame(8, count($list));

    }

   
}