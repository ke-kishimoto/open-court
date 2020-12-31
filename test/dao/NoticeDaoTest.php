<?php
require_once "vendor/autoload.php";
require_once(__DIR__.'/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/BaseDao.php');
require_once('./model/dao/NoticeDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity/BaseEntity.php');
require_once('./model/entity/Notice.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use dao\NoticeDao;
use entity\Notice;
use PHPUnit\DbUnit\Operation\None;

class NoticeDaoTest extends TestCase
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
                'notice' => [
                    [
                        'id' => 1,
                        'title' => 'サーバー移行のお知らせ',
                        'content' => '2020年12月31日にサーバーを移行します。',
                        'register_date' => '2020-01-01 17:15:23',
                        'update_date' => '2020-01-01 17:15:23',
                        
                    ],
                    
                ],
            ]
        );
    }

    public function testSelectById()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new NoticeDao();
        $notice = $dao->selectById(1);
        $this->assertSame('1', $notice['id']);
        $this->assertSame('サーバー移行のお知らせ', $notice['title']);
        $this->assertSame('2020年12月31日にサーバーを移行します。', $notice['content']);
    }

    public function testInsert()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new NoticeDao();

        $notice = new Notice();
        $notice->title = 'メンバー募集';
        $notice->content = '開発メンバーを募集します。';

        $dao->insert($notice);

        $notice = $dao->selectById(2);
        $this->assertSame('2', $notice['id']);
        $this->assertSame('1', $notice['delete_flg']);
        $this->assertSame('メンバー募集', $notice['title']);
        $this->assertSame('開発メンバーを募集します。', $notice['content']);

    }

    public function testUpdate()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new NoticeDao();

        $notice = new Notice();
        $notice->id = 1;
        $notice->deleteFlg = 9;
        $notice->title = '開発メンバー募集';
        $notice->content = 'プログラミングに興味ある人募集';

        $dao->update($notice);

        $notice = $dao->selectById(1);
        $this->assertSame('1', $notice['id']);
        $this->assertSame('9', $notice['delete_flg']);
        $this->assertSame('開発メンバー募集', $notice['title']);
        $this->assertSame('プログラミングに興味ある人募集', $notice['content']);
    }

  
}