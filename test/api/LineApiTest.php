<?php
require_once(__DIR__.'/../dao/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/ConfigDao.php');
require_once('./model/dao/InquiryDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity/Config.php');
require_once('./model/entity/Participant.php');
require_once('./model/entity/Inquiry.php');
require_once(__DIR__.'/../../controller/api/LineApi.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use api\LineApi;
use entity\Inquiry;
use entity\Participant;

class LineApiTest extends TestCase
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
                'config' => [
                    [
                        'id' => 1,
                        'line_token' => 'hzgfhEZuegOKyg8tcY6j6wAGLCeKjof2UMiKsg4OJMX',
                        'system_title' => 'event calendar',
                        'bg_color' => 'orange',
                        'logo_img_path' => '',
                        'register_date' => '2020-01-01 17:15:23',
                        'update_date' => '2020-01-01 17:15:23',
                        'waiting_flg_auto_update' => '1'
                    ],
                    
                ],
                'inquiry' => [
                    [
                        'id' => 1,
                        'game_id' => 1,
                        'name' => 'test taro',
                        'email' => 'test@test.com',
                        'content' => '問い合わせ内容',
                        'status_flg' => 0,
                        'register_date' => '2020-01-01 17:15:23',
                        'update_date' => '2020-01-01 17:15:23'
                    ],
                ]
            ]
        );
    }

    // 予約の通知
    public function testReserveNotify()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $api = new LineApi();

        $participant = new Participant();
        $participant->name = 'kishimoto';
        $participant->email = 'kishimoto@gmail.com';
        $participant->occupation = 1;
        $participant->sex = 1;

        $result = $api->reserve_notify($participant, 'イベントサンプル', '2020-01-01', 1);
        $this->assertTrue($result);

    }

    // キャンセルの通知
    public function testCancelNotify()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $api = new LineApi();

        $participant = new Participant();
        $participant->name = 'kishimoto';

        $result = $api->cancel_notify($participant->name, 'イベントサンプル', '2020-01-01', 1);
        $this->assertTrue($result);
    }

    // 複数件の予約
    public function testMultipleReserve()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $api = new LineApi();

        $result = $api->multiple_reserve('ksuke', 3);
        $this->assertTrue($result);

    }
    // お問い合わせ
    public function testiInquiry()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $api = new LineApi();
        $inquiry = new Inquiry();

        $inquiry->gameTitle = 'ビギナーズ';
        $inquiry->name = 'ksuke';
        $inquiry->email = 'kishimoto@gmail.com';
        $inquiry->content = 'はじめました。';

        $result = $api->inquiry($inquiry);
        $this->assertTrue($result);

    }
}