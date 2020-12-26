<?php
require_once "vendor/autoload.php";
require_once(__DIR__.'/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/InquiryDao.php');
require_once('./model/entity/Inquiry.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use dao\InquiryDao;
use entity\Inquiry;

class InquiryDaoTest extends TestCase
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
                    [
                        'id' => 2,
                        'game_id' => 1,
                        'name' => 'test jiro',
                        'email' => 'jiro@test.com',
                        'content' => '問い合わせ内容2',
                        'status_flg' => 1,
                        'register_date' => '2020-01-01 17:15:23',
                        'update_date' => '2020-01-01 17:15:23'
                    ],
                ],
            ]
        );
    }

    public function testGetInquiry()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new InquiryDao();
        $inquiry = $dao->getInquiry(1);
        $this->assertSame('1', $inquiry['id']);
        $this->assertSame('1', $inquiry['game_id']);
        $this->assertSame('test taro', $inquiry['name']);
        $this->assertSame('test@test.com', $inquiry['email']);
        $this->assertSame('問い合わせ内容', $inquiry['content']);
        $this->assertSame('0', $inquiry['status_flg']);
    }

    public function testGetInquiryList()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new InquiryDao();
        $inquiryList = $dao->getInquiryList();
        $this->assertSame(2, count($inquiryList));
    }

    public function testInsert()
    {
        $dataSet = $this->getConnection()->createDataSet();

        $inquiry = new Inquiry();
        $inquiry->gameId = null;
        $inquiry->name = 'alice';
        $inquiry->email = 'example@xxx.com';
        $inquiry->content = '問い合わせテスト';
        $inquiry->statusFlg = 0;

        $dao = new InquiryDao();
        $dao->insert($inquiry);

        $inquiryList = $dao->getInquiryList();
        $this->assertSame(3, count($inquiryList));

        $inquiry = $dao->getInquiry(3);
        $this->assertSame('3', $inquiry['id']);
        $this->assertSame(null, $inquiry['game_id']);
        $this->assertSame('alice', $inquiry['name']);
        $this->assertSame('example@xxx.com', $inquiry['email']);
        $this->assertSame('問い合わせテスト', $inquiry['content']);
        $this->assertSame('0', $inquiry['status_flg']);


    }

    public function testUpdateStatusFlg()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new InquiryDao();
        $dao->updateStatusFlg(1);
        $inquiry = $dao->getInquiry(1);
        $this->assertSame('1', $inquiry['status_flg']);

        $dao->updateStatusFlg(2);
        $inquiry = $dao->getInquiry(2);
        $this->assertSame('0', $inquiry['status_flg']);
    }

}