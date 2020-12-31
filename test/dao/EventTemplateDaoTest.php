<?php
require_once "vendor/autoload.php";
require_once(__DIR__.'/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/BaseDao.php');
require_once('./model/dao/EventTemplateDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity/BaseEntity.php');
require_once('./model/entity/EventTemplate.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use dao\EventTemplateDao;
use entity\EventTemplate;

class EventTemplateDaoTest extends TestCase
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
                'event_template' => [
                    [
                        'id' => 1,
                        'template_name' => 'テンプレートサンプル',
                        'title' => 'イベントサンプル',
                        'short_title' => 'イベさん',
                        'place' => '沖縄市',
                        'limit_number' => 20,
                        'detail' => '沖縄市でやるイベントです。',
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 2,
                        'template_name' => 'テンプレートサンプル2',
                        'title' => 'イベントサンプル2',
                        'short_title' => 'イベさん2',
                        'place' => '沖縄市越来',
                        'limit_number' => 10,
                        'detail' => '沖縄市でやるイベントです。',
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                ],
            ]
        );
    }

    public function testGetEventTemplateList()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new EventTemplateDao();

        $list = $dao->getEventTemplateList();
        $this->assertSame(2, count($list));
    }

    public function testGetEventTemplate()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new EventTemplateDao();

        $template = $dao->selectById(1);
        $this->assertSame('1', $template['id']);
        $this->assertSame('テンプレートサンプル', $template['template_name']);
        $this->assertSame('イベントサンプル', $template['title']);
        $this->assertSame('イベさん', $template['short_title']);
        $this->assertSame('沖縄市', $template['place']);
        $this->assertSame('20', $template['limit_number']);
        $this->assertSame('沖縄市でやるイベントです。', $template['detail']);
        $this->assertSame('100', $template['price1']);
        $this->assertSame('200', $template['price2']);
        $this->assertSame('300', $template['price3']);

    }

    public function testInsert()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new EventTemplateDao();

        $template = new EventTemplate();
        $template->templateName = 'オープンコート1';
        $template->title = 'オープンコード1 aaa';
        $template->shortTitle = 'オープン那覇';
        $template->place = '那覇市';
        $template->limitNumber = 25;
        $template->detail = 'みんなでわいわい';
        $template->price1 = 100;
        $template->price2 = 200;
        $template->price3 = 300;

        $dao->insert($template);

        $list = $dao->getEventTemplateList();
        $this->assertSame(3, count($list));

        $template = $dao->selectById(3);
        $this->assertSame('3', $template['id']);
        $this->assertSame('オープンコート1', $template['template_name']);
        $this->assertSame('オープンコード1 aaa', $template['title']);
        $this->assertSame('オープン那覇', $template['short_title']);
        $this->assertSame('那覇市', $template['place']);
        $this->assertSame('25', $template['limit_number']);
        $this->assertSame('みんなでわいわい', $template['detail']);

    }

    public function testUpdate()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new EventTemplateDao();

        $template = new EventTemplate();
        $template->id = 2;
        $template->templateName = 'ビギナーズ';
        $template->title = 'ビギナーズ1 aaa';
        $template->shortTitle = 'びぎ';
        $template->place = '浦添';
        $template->limitNumber = 27;
        $template->detail = '初心者向け';

        $dao->update($template);

        $template = $dao->selectById(2);
        $this->assertSame('2', $template['id']);
        $this->assertSame('ビギナーズ', $template['template_name']);
        $this->assertSame('ビギナーズ1 aaa', $template['title']);
        $this->assertSame('びぎ', $template['short_title']);
        $this->assertSame('浦添', $template['place']);
        $this->assertSame('27', $template['limit_number']);
        $this->assertSame('初心者向け', $template['detail']);
    }

    public function testDelete()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new EventTemplateDao();

        $dao->delete(2);

        $list = $dao->getEventTemplateList();
        $this->assertSame(1, count($list));
    }
}