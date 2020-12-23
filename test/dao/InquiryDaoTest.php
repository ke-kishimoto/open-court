<?php
require_once('./model/dao/ConfigDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/MampPDO.php');
require_once('./model/entity/Config.php');

use PHPUnit\Framework\TestCase;
use dao\ConfigDao;
use entity\Config;

class InquiryDaoTest extends TestCase
{

    public function testGetConfig() 
    {
        $configDao = new ConfigDao();
        $config = $configDao->getConfig(1);
        var_dump($config);

        $this->assertNotEmpty($config);
    }

    public function testUpdate()
    {
        $configDao = new ConfigDao();
        $configDao->getPdo()->beginTransaction();
        $config = new Config(1, '123', 'title', 'blue', '//img//logo.png', '1');
        $configDao->update($config);

        $config = $configDao->getConfig(1);
        $this->assertSame('1', $config['id']);
        $this->assertSame('123', $config['line_token']);
        $this->assertSame('title', $config['system_title']);
        $this->assertSame('blue', $config['bg_color']);
        $this->assertSame('//img//logo.png', $config['logo_img_path']);
        $this->assertSame('1', $config['waiting_flg_auto_update']);

        $configDao->getPdo()->rollback();
    }

  
}