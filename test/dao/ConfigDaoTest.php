<?php
require_once "vendor/autoload.php";
require_once(__DIR__.'/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/ConfigDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity/Config.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use dao\ConfigDao;
use entity\Config;

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
                'config' => [
                    [
                        'id' => 1,
                        'line_token' => 'abcde',
                        'system_title' => 'event calendar',
                        'bg_color' => 'orange',
                        'logo_img_path' => '',
                        'register_date' => '2020-01-01 17:15:23',
                        'update_date' => '2020-01-01 17:15:23',
                        'waiting_flg_auto_update' => '1'
                    ],
                    
                ],
            ]
        );
    }

    public function testGetConfig()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new ConfigDao();
        $config = $dao->getConfig(1);
        $this->assertSame('1', $config['id'], 'id');
        $this->assertSame('abcde', $config['line_token'], 'line_token');
        $this->assertSame('event calendar', $config['system_title'], 'system_title');
        $this->assertSame('orange', $config['bg_color'], 'bg_color');
        $this->assertSame('', $config['logo_img_path'], 'logo_img_path');
        $this->assertSame('2020-01-01 17:15:23', $config['register_date'], 'register_date');
        $this->assertSame('2020-01-01 17:15:23', $config['update_date'], 'update_date');
        $this->assertSame('1', $config['waiting_flg_auto_update'], 'waiting_flg_auto_update');
    }

    public function testUpdate()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new ConfigDao();

        // $config = new Config(1, '123', 'title', 'blue', '//img//logo.png', '1');
        $config = new Config();
        $config->id = 1;
        $config->lineToken = '123';
        $config->systemTitle = 'title';
        $config->bgColor = 'blue';
        $config->logoImgPath = '//img//logo.png';
        $config->waitingFlgAutoUpdate = '1';

        $dao->update($config);

        $config = $dao->getConfig(1);
        $this->assertSame('1', $config['id']);
        $this->assertSame('123', $config['line_token']);
        $this->assertSame('title', $config['system_title']);
        $this->assertSame('blue', $config['bg_color']);
        $this->assertSame('//img//logo.png', $config['logo_img_path']);
        $this->assertSame('1', $config['waiting_flg_auto_update']);

    }
}